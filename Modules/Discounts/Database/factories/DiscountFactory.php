<?php

namespace Modules\Discounts\Database\factories;

use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Discounts\Entities\Discount;
use Modules\Discounts\Entities\Lookups\DiscountTypeLookups;

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
            'type' => DiscountTypeLookups::PERCENTAGE,
            'is_active' => 1,
            'amount' => $this->faker->numberBetween(1, 90),
            'from' => \Carbon\Carbon::now(),
            'to' => (\Carbon\Carbon::now())->add(5, 'day'),
        ];
    }

    public function specialPercentage()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => DiscountTypeLookups::SPECIAL_PERCENTAGE,
            ];
        });
    }
}
