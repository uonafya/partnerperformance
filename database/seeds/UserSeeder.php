<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	\App\User::create(['name' => 'joel', 'email' => 'joelkith@gmail.com', 'password' => 'password', 'partner_id' => 55])

    }
}
