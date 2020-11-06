<?php

namespace Modules\Orders\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\Discounts\Entities\Discount;
use Modules\Discounts\Entities\SpecialDiscount;
use Modules\Products\Entities\Product;

class DiscountOrderDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Model::unguard();
        $shirt = Product::where('name', 'T-shirt')->first();
        $jacket = Product::where('name', 'Jacket')->first();
        $shoes = Product::where('name', 'Shoes')->first();
        $pants = Product::where('name', 'Pants')->first();
        if (empty($shirt)) {
            $shirt = Product::factory(['name' => 'T-shirt', 'price' => 10.99])->create();
        }

        if (empty($pants)) {
            $pants = Product::factory(['name' => 'Pants', 'price' => 14.99])->create();
        }

        if (empty($shoes)) {
            $shoes = Product::factory()->hasDiscounts(
                Discount::factory(),
                [
                    'type' => 'percentage',
                    'amount' => 10,
                ]
            )->create(['name' => 'Shoes', 'price' => 24.99]);
        }

        if (empty($jacket)) {
            $jacket = Product::factory()->hasDiscounts(
                Discount::factory(),
                [
                    'type' => 'special_percentage',
                    'amount' => 50,
                ]
            )->create(['name' => 'Jacket', 'price' => 19.99]);
            $shirt = SpecialDiscount::factory([
                'product_id' => $shirt->id,
                'qty' => 2,
                'discount_id' => $jacket->discounts[0]->id,
            ])->create();
        }
    }
}
