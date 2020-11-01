<?php

namespace Modules\Discounts\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Generator as Faker;
use Modules\Discounts\Entities\Discount;

class DiscountFactory extends Factory
{
    protected $model = Discount::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'type' => $this->faker->randomElement([
                "percentage", "amount", "special_amount", "special_percentage",
            ]),
            'is_active' => 1,
            'amount' => $this->faker->numberBetween(1, 90),
            'from' => \Carbon\Carbon::now(),
            'to' => (\Carbon\Carbon::now())->add(5, 'day'),
        ];
    }
}
