<?php

namespace Modules\Orders\Tests\Unit\Http\Controllers;

use Faker\Factory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Discounts\Entities\Discount;
use Modules\Discounts\Entities\SpecialDiscount;
use Modules\Orders\Entities\Currency;
use Modules\Orders\Entities\Order;
use Modules\Orders\Entities\ProductsOrder;
use Modules\Orders\Services\Interfaces\OrderServiceInterface;
use Modules\Products\Entities\Product;
use Tests\TestCase;
use Modules\Orders\Exceptions\InvalidCurrencyException;

class OrdersControllerTest extends TestCase
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

    public function test_create_order_successfully_response()
    {
        $shirt = Product::factory(['name' => 'T-shirt', 'price' => 10.99])->create();
        $jacket = Product::factory()->hasDiscounts(
            Discount::factory(),
            [
                'type' => 'special_percentage',
                'amount' => 50,
            ]
        )->create(['name' => 'Jacket', 'price' => 19.99]);

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
        )->create(['name' => 'Shouse', 'price' => 24.99]);

        $subTotal = $jacket->price + 2 * $shirt->price + $shouse->price;
        $taxes = .14 * $subTotal;
        $total = $taxes + ($jacket->price * .5) + ($shirt->price * 2) + ($shouse->price - ($shouse->price * .1));
        $currency = Currency::where('code', 'USD')->first();
        $order = Order::create([
            "currency_ratio" => 1,
            "currency_id" => $currency->id,
            "sub_total" => $subTotal,
            "total" => $total,
            "taxes" => $taxes,
        ]);
        ProductsOrder::create([
            "qty" => 2,
            "order_id" => $order->id,
            "product_id" => $shirt->id]);

        ProductsOrder::create([
            "qty" => 1,
            "discount_off" => (.1 * $shouse->price),
            "order_id" => $order->id,
            "discount_id" => $shouse->discounts[0]->id,
            "product_id" => $shouse->id]);

        ProductsOrder::create([
            "qty" => 1,
            "discount_off" => (.5 * $jacket->price),
            "order_id" => $order->id,
            "discount_id" => $jacket->discounts[0]->id,
            "product_id" => $jacket->id]);
        $cart = [$shirt->name, $shirt->name, $jacket->name, $shouse->name];

        $orderServiceMock = \Mockery::mock(OrderServiceInterface::class);
        $orderServiceMock->shouldReceive('createOrder')
            ->once()
            ->with($cart, 'USD')
            ->andReturn($order)->getMock();
        $this->app->instance(OrderServiceInterface::class, $orderServiceMock);
        $response = $this->postJson('orders/store', ['cart' => $cart, 'currency' => 'USD']);
        $response->assertStatus(201);
        $response->assertJson(
            [
                "data" => [
                    "Subtotal" => "$66.96",
                    "Taxes" => "$9.3744",
                    "discount" => [
                        [
                            "name" => "Shouse",
                            "discount_off" => "$2.499",
                            "discount_amount" => "10.0%",
                        ],
                        [
                            "name" => "Jacket",
                            "discount_off" => "$9.995",
                            "discount_amount" => "50.0%",
                        ],
                    ],
                    "total" => "$63.8404",
                ],
            ]
        );
    }

    public function test_create_order_invalid_request_response()
    {
        $shirt = Product::factory(['name' => 'T-shirt', 'price' => 10.99])->create();
        $jacket = Product::factory()->hasDiscounts(
            Discount::factory(),
            [
                'type' => 'special_percentage',
                'amount' => 50,
            ]
        )->create(['name' => 'Jacket', 'price' => 19.99]);

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
        )->create(['name' => 'Shouse', 'price' => 24.99]);

        $subTotal = $jacket->price + 2 * $shirt->price + $shouse->price;
        $taxes = .14 * $subTotal;
        $total = $taxes + ($jacket->price * .5) + ($shirt->price * 2) + ($shouse->price - ($shouse->price * .1));
        $currency = Currency::where('code', 'USD')->first();
        $order = Order::create([
            "currency_ratio" => 1,
            "currency_id" => $currency->id,
            "sub_total" => $subTotal,
            "total" => $total,
            "taxes" => $taxes,
        ]);
        ProductsOrder::create([
            "qty" => 2,
            "order_id" => $order->id,
            "product_id" => $shirt->id]);

        ProductsOrder::create([
            "qty" => 1,
            "discount_off" => (.1 * $shouse->price),
            "order_id" => $order->id,
            "discount_id" => $shouse->discounts[0]->id,
            "product_id" => $shouse->id]);

        ProductsOrder::create([
            "qty" => 1,
            "discount_off" => (.5 * $jacket->price),
            "order_id" => $order->id,
            "discount_id" => $jacket->discounts[0]->id,
            "product_id" => $jacket->id]);
        $orderServiceMock = \Mockery::spy(OrderServiceInterface::class);
        $orderServiceMock->shouldNotHaveReceive('createOrder');
        $this->app->instance(OrderServiceInterface::class, $orderServiceMock);
        $response = $this->postJson('orders/store', ['cart' => [], 'currency' => 'USD']);
        $response->assertStatus(422);
        $response->assertJson(
            [
                "message" => "The given data was invalid.",
                "errors" => [
                    "cart" => [
                        "The cart field is required.",
                    ],
                ],
            ]
        );
    }

    public function test_create_order_invalid_currency_exception_response()
    {
        $shirt = Product::factory(['name' => 'T-shirt', 'price' => 10.99])->create();
        $jacket = Product::factory()->hasDiscounts(
            Discount::factory(),
            [
                'type' => 'special_percentage',
                'amount' => 50,
            ]
        )->create(['name' => 'Jacket', 'price' => 19.99]);

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
        )->create(['name' => 'Shouse', 'price' => 24.99]);

        $subTotal = $jacket->price + 2 * $shirt->price + $shouse->price;
        $taxes = .14 * $subTotal;
        $total = $taxes + ($jacket->price * .5) + ($shirt->price * 2) + ($shouse->price - ($shouse->price * .1));
        $currency = Currency::where('code', 'USD')->first();
        $order = Order::create([
            "currency_ratio" => 1,
            "currency_id" => $currency->id,
            "sub_total" => $subTotal,
            "total" => $total,
            "taxes" => $taxes,
        ]);
        ProductsOrder::create([
            "qty" => 2,
            "order_id" => $order->id,
            "product_id" => $shirt->id]);

        ProductsOrder::create([
            "qty" => 1,
            "discount_off" => (.1 * $shouse->price),
            "order_id" => $order->id,
            "discount_id" => $shouse->discounts[0]->id,
            "product_id" => $shouse->id]);

        ProductsOrder::create([
            "qty" => 1,
            "discount_off" => (.5 * $jacket->price),
            "order_id" => $order->id,
            "discount_id" => $jacket->discounts[0]->id,
            "product_id" => $jacket->id]);
        $cart = [$shirt->name, $shirt->name, $jacket->name, $shouse->name];
        $orderServiceMock = \Mockery::mock(OrderServiceInterface::class);
        $orderServiceMock->shouldReceive('createOrder')
            ->once()
            ->with($cart, 'USDDDD')
            ->andThrow(new InvalidCurrencyException())->getMock();
        $this->app->instance(OrderServiceInterface::class, $orderServiceMock);
        $response =$this->postJson('orders/store', ['cart' => $cart, 'currency' => 'USDDDD']);
        $response->assertStatus(422);
        $response->assertJson([ "code" => 422,"message" => "this is invalid currency"]);
    }
}
