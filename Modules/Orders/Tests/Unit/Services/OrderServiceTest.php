<?php

namespace Modules\Orders\Tests\Feature\Http\Controllers;

use Faker\Factory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Discounts\Entities\Discount;
use Modules\Discounts\Entities\SpecialDiscount;
use Modules\Infrastructure\Helpers\ConvertCurrencyConverterFetcher;
use Modules\Orders\Entities\Currency;
use Modules\Orders\Entities\Order;
use Modules\Orders\Exceptions\InvalidCurrencyException;
use Modules\Orders\Repositories\Interfaces\OrderRepositoryInterface;
use Modules\Orders\Services\Interfaces\OrderServiceInterface;
use Modules\Orders\Services\OrderService;
use Modules\Products\Entities\Product;
use Tests\TestCase;

class OrderServiceTest extends TestCase
{
    use RefreshDatabase;
    use DatabaseMigrations;

    public function tearDown(): void
    {
        \Mockery::close();
    }

    public function setUp(): void
    {
        parent::setUp();
        \Artisan::call('db:seed', ['--class' => 'Modules\Orders\Database\Seeders\CurrencyOrderDatabaseSeeder']);
    }

    public function test_create_order_successfully_with_USD()
    {
        $shirt = Product::factory(['name' => 'T-shirt'])->create();
        $jacket = Product::factory()->hasDiscounts(
            Discount::factory(),
            [
                'type' => 'special_percentage',
                'amount' => 50,
            ]
        )->create(['name' => 'Jacket']);

        SpecialDiscount::factory([
            'product_id' => $shirt->id,
            'qty' => 2,
            'discount_id' => $jacket->discounts[0]->id,
        ])->create();
        $shouse = Product::factory()->hasDiscounts(
            Discount::factory(),
            [
                'type' => 'percentage',
                'amount' => 10,
            ]
        )->create(['name' => 'Shouse']);
        $orderRepository = resolve(OrderRepositoryInterface::class);
        $orderServiceMock = \Mockery::mock(OrderService::class, [$orderRepository])->makePartial();
        $cart = [$shirt->name, $shirt->name, $jacket->name, $shouse->name];
        $orderServiceMock->shouldReceive('convertOrderPrice')
            ->once()
            ->with(1, 'USD')
            ->andReturn([
                'id' => (Currency::where('code', 'USD')->first())->id,
                'ratio' => 1,
            ]);
        $subTotal = $shirt->price + $shirt->price + $jacket->price + $shouse->price;
        $taxes = .14 * ($shirt->price + $shirt->price + $jacket->price + $shouse->price);
        $totalPrice = ($jacket->price * .5) + ($shirt->price * 2) + ($shouse->price - ($shouse->price * .1)) + $taxes;
        $order = $orderServiceMock->createOrder($cart, 'USD');
        $this->assertInstanceOf(Order::class, $order);
        $this->assertEquals($order->taxes, $taxes);
        $this->assertEquals($order->total, $totalPrice);
        $this->assertEquals($order->sub_total, $subTotal);
        $this->assertDatabaseHas('products_orders', ['product_id' => $shouse->id]);
        $this->assertDatabaseHas('products_orders', ['product_id' => $shirt->id]);
        $this->assertDatabaseHas('products_orders', ['product_id' => $jacket->id]);
    }

    public function test_create_order_successfully_with_EGP()
    {
        $shirt = Product::factory(['name' => 'T-shirt'])->create();
        $jacket = Product::factory()->hasDiscounts(
            Discount::factory(),
            [
                'type' => 'special_percentage',
                'amount' => 50,
            ]
        )->create(['name' => 'Jacket']);

        SpecialDiscount::factory([
            'product_id' => $shirt->id,
            'qty' => 2,
            'discount_id' => $jacket->discounts[0]->id,
        ])->create();

        $shouse = Product::factory()->hasDiscounts(
            Discount::factory(),
            [
                'type' => 'percentage',
                'amount' => 10,
            ]
        )->create(['name' => 'Shouse']);
        $currencyMock = \Mockery::mock(ConvertCurrencyConverterFetcher::class, ['amount' => 1, 'to' => 'EGP', 'from' => 'USD']);
        $currencyMock = $currencyMock->shouldReceive('convert')
            ->once()
            ->andReturn([
                "ratio" => 15.72,
                "from" => [
                    "value" => 1.0,
                    "symbol" => "$",
                    "name" => "US Dollar",
                ],
                "converted" => [
                    "value" => 15.72,
                    "symbol" => "£",
                    "name" => "Egyptian Pound",
                ],
            ])->getMock();
        app()->bind(ConvertCurrencyConverterFetcher::class, function () use ($currencyMock) {
            return $currencyMock;
        });
        $orderService = $this->app->make(OrderServiceInterface::class);
        $ratio = 15.72 / 1;
        $cart = [$shirt->name, $shirt->name, $jacket->name, $shouse->name];

        $subTotal = $shirt->price + $shirt->price + $jacket->price + $shouse->price;
        $taxes = .14 * ($shirt->price + $shirt->price + $jacket->price + $shouse->price);
        $totalPrice = ($jacket->price * .5) + ($shirt->price * 2) + ($shouse->price - ($shouse->price * .1)) + $taxes;
        $order = $orderService->createOrder($cart, 'EGP');
        $this->assertInstanceOf(Order::class, $order);
        $this->assertEquals($order->taxes, $taxes);
        $this->assertEquals($order->total, $totalPrice);
        $this->assertEquals($order->sub_total, $subTotal);
        $this->assertDatabaseHas('currency', ['code' => "EGP"]);
        $this->assertEquals($order->currency_ratio, $ratio);
        $this->assertEquals($order->currency_id, (Currency::where('code', 'EGP')->first())->id);
        $this->assertDatabaseHas('products_orders', ['product_id' => $shouse->id]);
        $this->assertDatabaseHas('products_orders', ['product_id' => $shirt->id]);
        $this->assertDatabaseHas('products_orders', ['product_id' => $jacket->id]);
    }

    public function test_create_order_with_invalid_currency_exception()
    {
        $this->expectException(InvalidCurrencyException::class);
        $shirt = Product::factory(['name' => 'T-shirt'])->create();
        $jacket = Product::factory()->hasDiscounts(
            Discount::factory(),
            [
                'type' => 'special_percentage',
                'amount' => 50,
            ]
        )->create(['name' => 'Jacket']);

        SpecialDiscount::factory([
            'product_id' => $shirt->id,
            'qty' => 2,
            'discount_id' => $jacket->discounts[0]->id,
        ])->create();
        $shouse = Product::factory()->hasDiscounts(
            Discount::factory(),
            [
                'type' => 'percentage',
                'amount' => 10,
            ]
        )->create(['name' => 'Shouse']);
        $orderRepository = resolve(OrderRepositoryInterface::class);
        $orderServiceMock = \Mockery::mock(OrderService::class, [$orderRepository])->makePartial();
        $cart = [$shirt->name, $shirt->name, $jacket->name, $shouse->name];
        $orderServiceMock->shouldReceive('convertOrderPrice')
            ->once()
            ->with(1, 'USDD')
            ->andThrow(InvalidCurrencyException::class);
        $orderServiceMock->createOrder($cart, 'USDD');
    }
}
