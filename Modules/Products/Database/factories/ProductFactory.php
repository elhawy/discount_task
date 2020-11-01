<?php

namespace Modules\Products\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Products\Entities\Product;

class ProductFactory extends Factory
{

    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->uniqueExists(),
            'price' => $this->faker->randomElement([10.99, 14.99, 19.99, 24.99]),
        ];
    }
    private function uniqueExists()
    {
        $names = ["T-shirt", "Shoes", "Jacket", 'Pants', 'Bags'];
        foreach ($names as $name) {
            $unique = Product::where('name', $name)->get();
            if ($unique->isEmpty()) {
                return $name;
            }
        }
        return $this->unique()->company;
    }
}
