<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ClassroomCreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'classroom:create
        {howMany : How many classrooms to create}
        {studentId : The student_id of the user you wish to create classrooms}
        {prefStartHour= : The student\'s preferred start time in hour}
        {prefStartMinute= : The student\'s preferred start time in minute}
        {prefEndHour= : The student\'s preferred end time in hour}
        {prefEndMinute= : The student\'s preferred end time in minute}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command creates a classroom';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
    }
}
