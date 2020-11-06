<?php

namespace Modules\Orders\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\Orders\Entities\Currency;

class CurrencyOrderDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Model::unguard();
        $currency = Currency::where('code', 'USD')->first();
        if (empty($currency)) {
            Currency::create(["code" => 'USD', 'symbol' => '$', 'name' => 'US Dollar']);
        }
    }
}
