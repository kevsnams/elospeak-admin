<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Student;
use App\ClassroomSchedulePreference;

class ClassroomCreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'classroom:create
        {studentId : The student_id of the user you wish to create classrooms}
        {prefStartHour : The student\'s preferred start time in hour}
        {prefStartMinute : The student\'s preferred start time in minute}
        {prefEndHour : The student\'s preferred end time in hour}
        {prefEndMinute : The student\'s preferred end time in minute}
        {startDate : The date where to start the classrooms}
        {howMany? : [Optional] How many classrooms to create. If left blank, it will create classrooms depending on how much balance the student has}';

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
        $studentId = $this->argument('studentId');
        $howMany = $this->argument('howMany');
        $startHour = $this->argument('prefStartHour');
        $startMinute = $this->argument('prefStartMinute');
        $endHour = $this->argument('prefEndHour');
        $endMinute = $this->argument('prefEndMinute');
        $startDate = $this->argument('startDate');

        
    }
}
