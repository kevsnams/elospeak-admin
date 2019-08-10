<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class InitialWebsiteSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('website_settings')->insert(
            [
                [
                    'key' => 'CLASSROOM.student_price',
                    'value' => '100'
                ],

                [
                    'key' => 'CLASSROOM.teacher_income',
                    'value' => '10'
                ]
            ]
        );
    }
}
