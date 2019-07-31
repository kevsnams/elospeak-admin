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
        for ($i = 0; $i < 20; $i++) {
            DB::table('classrooms')->insert([
                'teacher_id' => 1,
                'student_id' => 1,
                'start' => '2019-07-30 07:00:00',
                'end' => '2019-07-30 07:30:00',
                'status' => 1
            ]);
        }
    }
}
