<?php

namespace Modules\Orders\Database\factories;

use Modules\Orders\Entities\Order;

class OrderFactory
{
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'currency_ratio' => 1,
            'currency_id' => 1,
            'taxes' => .14,
            'sub_total' => 100,
            'total' => 114,
        ];
    }
}
