<?php

use App\CustomerMessage;
use Illuminate\Database\Seeder;

class CustomerMessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(CustomerMessage::class, 20)->create();
    }
}
