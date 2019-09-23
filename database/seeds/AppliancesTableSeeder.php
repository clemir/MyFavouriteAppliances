<?php

use App\Appliance;
use Illuminate\Database\Seeder;

class AppliancesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Appliance::class, 50)->create();
    }
}
