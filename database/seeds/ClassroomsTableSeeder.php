<?php

use Illuminate\Database\Seeder;

class ClassroomsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 31; $i++) {
            DB::table('classrooms')->insert([
                'teacher_id' => 1,
                'student_id' => 1,
                'start' => '2020-03-'. $i .' 07:00:00',
                'end' => '2020-03-'. $i .' 07:30:00',
                'status' => 1,
                'price' => 300,
                'invoice_id' => 1
            ]);
        }
    }
}
