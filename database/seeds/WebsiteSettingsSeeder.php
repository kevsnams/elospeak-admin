<?php

use Illuminate\Database\Seeder;

class WebsiteSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('website_settings')->insert([
            [
                'key' => 'CLASSROOM.price_per_class',
                'value' => '400'
            ],

            [
                'key' => 'CLASSROOM.duration',
                'value' => '25'
            ],

            [
                'key' => 'TEACHER.salary_per_class',
                'value' => '50'
            ],

            [
                'key' => 'CLASSROOM.price_per_class_weekend',
                'value' => '800'
            ],

            [
                'key' => 'CLASSROOM.start_hour',
                'value' => '8'
            ],

            [
                'key' => 'CLASSROOM.end_hour',
                'value' => '23'
            ],
        ]);
    }
}
