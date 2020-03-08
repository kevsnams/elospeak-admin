<?php

use Illuminate\Database\Seeder;

class StudentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 20; $i++) {
            DB::table('students')->insert([
                'username' => Str::random(10),
                'password' => bcrypt('123'),
                'email' => Str::random(10) .'@gmail.com',
                'skype' => Str::random(10),
                'full_name' => 'Dummy '. ucfirst(Str::random(10)),
                'birthday' => '1992-03-10'
            ]);
        }
    }
}
