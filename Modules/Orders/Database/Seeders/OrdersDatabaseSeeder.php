<?php

namespace Modules\Orders\Database\Seeders;

use Modules\Orders\Database\Seeders\DiscountOrderDatabaseSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class OrdersDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(DiscountOrderDatabaseSeeder::class);
    }
}
