<?php

namespace Modules\Discounts\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Generator as Faker;
use Modules\Discounts\Entities\SpecialDiscount;
use Modules\Discounts\Entities\Discount;
use Modules\Products\Entities\Product;

class SpecialDiscountFactory extends Factory
{
    protected $model = SpecialDiscount::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'discount_id' => (Discount::factory()->create())->id,
            'product_id' => (Product::factory()->create())->id,
            'qty' => $this->faker->numberBetween(1, 4),
        ];
    }
}
