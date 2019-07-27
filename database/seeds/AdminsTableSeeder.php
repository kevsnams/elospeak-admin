<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admins')->insert([
            'username' => 'admin1',
            'password' => bcrypt('69lego69')
        ]);

        DB::table('admins')->insert([
            'username' => 'admin2',
            'password' => bcrypt('69lego69')
        ]);
    }
}
