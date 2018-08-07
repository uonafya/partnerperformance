<?php

use Illuminate\Database\Seeder;

class DivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	\App\Division::create(['name' => 'Partners']);
    	\App\Division::create(['name' => 'Counties']);
    	\App\Division::create(['name' => 'Subcounties']);
    	\App\Division::create(['name' => 'Wards']);
        \App\Division::create(['name' => 'Facilities']);
    	\App\Division::create(['name' => 'Funding Agencies']);
    }
}
