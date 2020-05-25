<?php

namespace App\Console\Commands;

use App\Classroom;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SetClassroomEnd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'classroom:add {classroom}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Adds the classroom to the queue that automatically sets the status to DONE if past end datetime';

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
        $classroom = Classroom::find($this->argument('classroom'));

        if ($classroom) {
            $end = Carbon::parse($classroom->end);


            if ($end->lessThanOrEqualTo(now())) {
                $classroom->status = Classroom::STATUS_DONE;
            } else {
                $doneDate = now();
                $doneDate->addSeconds($end->diffInSeconds($doneDate) + 1);

                echo $doneDate->format('Y-m-d H:i:s');


            }
        }
    }
}
