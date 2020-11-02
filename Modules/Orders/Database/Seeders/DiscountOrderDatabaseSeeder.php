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
        $shouse = Product::where('name', 'Shouse')->first();
        if (empty($shirt)) {
            $shirt = Product::factory(['name' => 'T-shirt'])->create();
        }
        if (empty($jacket)) {
            $jacket = Product::factory()->hasDiscounts(
                Discount::factory(),
                [
                    'type' => 'special_percentage',
                    'amount' => 50,
                ]
            )->create(['name' => 'Jacket']);
            $shirt = SpecialDiscount::factory([
                'product_id' => $shirt->id,
                'qty' => 2,
                'discount_id' => $jacket->discounts[0]->id,
            ])->create();
        }
        if (empty($shouse)) {
            $shouse = Product::factory()->hasDiscounts(
                Discount::factory(),
                [
                    'type' => 'percentage',
                    'amount' => 10,
                ]
            )->create(['name' => 'Shouse']);
        }
    }
}
