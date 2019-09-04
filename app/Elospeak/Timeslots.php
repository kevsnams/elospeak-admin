<?php
declare(strict_types=1);

namespace App\Elospeak;

use App\WebsiteSetting;
use App\Classroom;
use App\Student;
use App\Teacher;

/**
 * Timeslots class
 */
class Timeslots {
    /**
     * The minimum Hours in a day
     */
    const HOUR_MIN = 1;

    /**
     * The maximum Hours in a day
     */
    const HOUR_MAX = 24;

    /**
     * The minimu minutes in an hour
     */
    const MINUTES_MIN = 0;

    /**
     * The maximum minutes in an hour
     */
    const MINUTES_MAX = 60;
    
    /**
     * @var Integer $start The time (in 24 Hour format) to start creating slots
     */
    private $start = 0;

    /**
     * @var Integer $end The time (in 24 Hour format) to end creating slots
     */
    private $end = 0;

    /**
     * @var Integer $duration The duration of a single class (in minutes)
     */
    private $duration = 0;

    /**
     * @var Integer $offset The offset between classes (in minutes)
     */
    private $offset = 0;

    /**
     * @var Array $webSettings The website settings. Ex: $this->webSettings['CLASSROOM']['duration']
     */
    private $webSettings = null;

    /**
     * @var Boolean $isLZ Is Leading Zero
     */
    private $isLZ = true;

    /**
     * @var Array $slots The time slots
     */
    private $slots = [];

    /**
     * Timeslots constructor
     *
     * This constructor can either have no param or 4 required params ($start, $end, $duration, $offset)
     * If no param, means it will get the $start, $end, $duration and $offset from the database through WebsiteSetting class
     *
     * @param Integer $start (optional) The time when to start creating slots
     * @param Integer $end (optional) The time when to end creating slots
     * @param Integer $duration (optional) The duration of a single classroom
     * @param Integer $offset (optional) The breaks between classes
     * 
     * @return Void
     * @throws $start > $end
     **/
    public function __construct()
    {
        if (func_num_args() > 0) {
            list($start, $end, $duration, $offset) = func_get_args();
        } else {
            $this->webSettings = parseWebSettings(WebsiteSetting::classrooms(['start_hour', 'end_hour', 'duration', 'offset'])->get());

            $end = $this->webSettings['CLASSROOM']['end_hour'];
            $start = $this->webSettings['CLASSROOM']['start_hour'];
            $offset = $this->webSettings['CLASSROOM']['offset'];
            $duration = $this->webSettings['CLASSROOM']['duration'];
        }

        $this->start = $start;
        $this->end = $end;
        $this->duration = $duration;
        $this->offset = $offset;

        if ($this->start > $this->end) {
            throw new \Exception('Start time must be less than end time');
        }

        $this->create();
    }

    /**
     * Create timeslots
     * 
     * This function creates timeslots based on given values of $this->start, $this->end, $this->duration and $this->offset
     * 
     * @return Void
     */
    private function create()
    {
        $builder = [];

        $currentHour = $this->start;
        $currentMinute = Timeslots::MINUTES_MIN;

        while ($currentHour != $this->end) {
            if ($currentMinute >= Timeslots::MINUTES_MAX) {
                $currentHour++;
                $currentMinute -= Timeslots::MINUTES_MAX;
            }
            
            $rangeStart = implode(':', [
                $this->isLZ ? ($currentHour < 10 ? '0'. $currentHour : $currentHour) : $currentHour,
                $this->isLZ ? ($currentMinute < 10 ? '0'. $currentMinute : $currentMinute) : $currentMinute
            ]);
            
            $currentMinute += $this->duration;

            if ($currentMinute >= Timeslots::MINUTES_MAX) {
                $currentHour++;
                $currentMinute -= Timeslots::MINUTES_MAX;
            }

            $rangeEnd = implode(':', [
                $this->isLZ ? ($currentHour < 10 ? '0'. $currentHour : $currentHour) : $currentHour,
                $this->isLZ ? ($currentMinute < 10 ? '0'. $currentMinute : $currentMinute) : $currentMinute
            ]);

            $currentMinute += $this->offset;

            $builder[] = [$rangeStart, $rangeEnd];
        }

        $this->slots = $builder;
    }
    
    /**
     * Flattens the timeslots array
     *
     * This function flattens the timeslots array. Example output:
     * [
     *  "08:00|08:30",
     *  "08:35|09:00",
     *  ...
     * ]
     *
     * @param String $delimiter (optional) The delimiter of a slot. Ex: $delimter = '|' Then, "08:00|09:30"
     * @return Array
     **/
    public function flatten($delimiter = '|')
    {
        $builder = [];
        foreach ($this->slots as $slot) {
            $builder[] = implode($delimiter, $slot);
        }

        return $builder;
    }
    
    /**
     * Unflatten the timeslots
     *
     * This function reverts the resulting array of flatten()
     *
     * @param String $delimiter (optional) The that sepeartes the start and end time
     * @return Array
     **/
    public function unflatten($delimiter = '|')
    {
        $builder = [];
        foreach ($this->slots as $slot) {
            $builder[] = explode($delimiter, $slot);
        }

        return $builder;
    }

    /**
     * Removes a timeslot from the timeslots array
     * 
     * Plucks a timeslot out of the timeslots array
     * 
     * @param Integer $start The start time
     * @param Integer $end The end time
     * @return Void
     */
    public function pluck($start, $end)
    {
        foreach ($this->slots as $i => $slot) {
            if ($slot[0] == $start && $end = $slot[1]) {
                unset($this->slots[$i]);
            }
        }
        $this->slots = array_values($this->slots);
    }

    /**
     * Set Leading Zeroes
     * 
     * If set to true, this adds leading zeroes to the time. Ex: 08:00-08:35
     * 
     * @param Boolean $lz If true, adds leading zeroes. If false, removes it.
     * @return Void
     */
    public function setLZ($lz)
    {
        $this->isLZ = !!$lz;
    }

    /**
     * Set slots
     * 
     * Manually set the values of $this->slots
     * 
     * @param Array $slots The value that will replace $this->slots
     * @param Boolean $delimiter If false, directly inject $slots to $this->slots. Otherwise, explode()
     * @return Void
     */
    public function setSlots($slots, $delimiter = false)
    {
        if ($delimiter === false) {
            $this->slots = $slots;
        } else {
            $this->slots = array_map(function($slot) use ($delimiter) {
                return explode($delimiter, $slot);
            }, $slots);
        }
    }

    /**
     * Get slots
     * 
     * Returns the timeslots
     * 
     * @return Array
     */
    public function getSlots()
    {
        return $this->slots;
    }

    /**
     * Returns available timeslots on a given date
     * 
     * This function accepts $date, $of and $id as parameter.
     * This will return the available timeslots of a given date.
     * 
     * @param String $date The date to look for available slots. Preferablly in Y-m-d format
     * @param String $of This can only be either of the two: 'student' or 'teacher'. If value is 'student' then it will look for student's available timeslots on a given date. Same with 'teacher'.
     * @param Integer $id The unique id of the 'student' or 'teacher'
     * 
     * @return Timeslots
     */
    static public function getAvailableByDate($date, $of, $id)
    {
        if (!in_array($of, ['student', 'teacher'])) {
            throw new \Exception('$of should either be "student" or "teacher"');
        }

        $model = null;
        switch ($of) {
            case 'student':
                $useColumn = 'student_id';
                $model = Student::find($id);
            break;

            case 'teacher':
                $useColumn = 'teacher_id';
                $model = Teacher::find($id);
            break;
        }

        if (!$model) {
            throw new \Exception("$useColumn = $id Not Found");
        }

        $date = date('Y-m-d', strtotime($date));

        $classrooms = Classroom::whereRaw('DATE(start) = ?', $date)->where($useColumn, $model->id)->get();

        $instance = new Timeslots();
        foreach ($classrooms as $classroom) {
            $instance->pluck($classroom->start->format('H:i'), $classroom->end->format('H:i'));
        }

        return $instance;
    }

    /**
     * Returns unavailable timeslots on a given date
     * 
     * This function accepts $date, $of and $id as parameter.
     * This will return the unavailable timeslots of a given date.
     * 
     * @param String $date The date to look for unavailable slots. Preferablly in Y-m-d format
     * @param String $of This can only be either of the two: 'student' or 'teacher'. If value is 'student' then it will look for student's available timeslots on a given date. Same with 'teacher'.
     * @param Integer $id The unique id of the 'student' or 'teacher'
     * 
     * @return Timeslots
     */
    static public function getUnavailableByDate($date, $of, $id)
    {
        if (!in_array($of, ['student', 'teacher'])) {
            throw new \Exception('$of should either be "student" or "teacher"');
        }

        $model = null;
        switch ($of) {
            case 'student':
                $useColumn = 'student_id';
                $model = Student::find($id);
            break;

            case 'teacher':
                $useColumn = 'teacher_id';
                $model = Teacher::find($id);
            break;
        }

        if (!$model) {
            throw new \Exception("$useColumn = $id Not Found");
        }

        $date = date('Y-m-d', strtotime($date));

        $classrooms = Classroom::whereRaw('DATE(start) = ?', [$date])->where($useColumn, $model->id)->orderBy('start', 'asc')->get();

        $slots = [];

        foreach ($classrooms as $classroom) {
            $slots[] = [
                $classroom->start->format('H:i'),
                $classroom->end->format('H:i')
            ];
        }

        $instance = new Timeslots();
        $instance->setSlots($slots);

        return $instance;
    }
}