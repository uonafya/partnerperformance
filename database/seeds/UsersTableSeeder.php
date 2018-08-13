<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	\App\User::create(['name' => 'joel', 'email' => 'joelkith@gmail.com', 'password' => 'password', 'partner_id' => 55, 'user_type_id' => 1]);
    	\App\User::create(['name' => 'tim', 'email' => 'tngugi@gmail.com', 'password' => 'password', 'partner_id' => 55, 'user_type_id' => 1]);
    	\App\User::create(['name' => 'joshua', 'email' => 'baksajoshua09@gmail.com', 'password' => 'password', 'partner_id' => 55, 'user_type_id' => 1]);
    	\App\User::create(['name' => 'James Batuka', 'email' => 'jbatuka@usaid.gov', 'password' => 'password', 'partner_id' => 55, 'user_type_id' => 1]);
    }
}
