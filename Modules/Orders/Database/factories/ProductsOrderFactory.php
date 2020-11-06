<?php

namespace Modules\Orders\Database\factories;

use Modules\Orders\Entities\ProductsOrder;
use Modules\Products\Entities\Product;

class ProductsOrderFactory
{

    protected $model = ProductsOrder::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $product = Product::factory()->create(['price' => 100]);
        $order = Order::factory()->create();
        return [
            'qty' => 1,
            'discount_off' => "",
            'order_id' => $order->id,
            'product_id' => $product->id,
            'discount_id' => "",
        ];
    }
}
