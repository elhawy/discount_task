<?php

namespace Modules\Orders\Tests\Feature\Http\Controllers;

use Faker\Factory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Discounts\Entities\Discount;
use Modules\Discounts\Entities\SpecialDiscount;
use Modules\Products\Entities\Product;
use Tests\TestCase;

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
        $this->artisan('db:seed');
    }

    public function test_create_order_successfully_response()
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
        $response = $this->postJson('orders/store', ['cart' => [$shirt->name, $shirt->name, $jacket->name, $shouse->name]]);
        $expectedPrice = ($jacket->price * .5) + ($shirt->price * 2) + ($shouse->price - ($shouse->price * .1));
        $order = $response->json()['order'];
        $response->assertStatus(200);
        $response->assertJsonStructure(["order" => ["order_id", "total_price"]]);
        $this->assertEquals($expectedPrice, $order["total_price"]);
    }
}
