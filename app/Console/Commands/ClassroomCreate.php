<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

use App\Student;
use App\ClassroomSchedulePreference;
use App\WebsiteSetting;

class ClassroomCreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'classroom:create
        {studentId : The student_id of the user you wish to create classrooms}
        {howMany? : [Optional] How many classrooms to create. If left blank, it will create classrooms based on how much balance the student has}';

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

        $student = Student::find($studentId);
    }
}
