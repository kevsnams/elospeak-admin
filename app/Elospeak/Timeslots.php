<?php
namespace App\Elospeak;

class Timeslots {

    const HOUR_MIN = 1;
    const HOUR_MAX = 24;
    const MINUTES_MIN = 0;
    const MINUTES_MAX = 60;

    private $start = 0;
    private $end = 0;
    private $duration = 0;
    private $offset = 0;

    private $slots = [];

    public function __construct($start, $end, $duration, $offset = 15)
    {
        $this->start = $start;
        $this->end = $end;
        $this->duration = $duration;
        $this->offset = $offset;

        if ($this->start > $this->end) {
            throw new Exception('Start time must be less than end time');
        }

        $this->create();
    }

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
            
            $rangeStart = implode(':', [$currentHour, $currentMinute < 10 ? '0'. $currentMinute : $currentMinute]);
            
            $currentMinute += $this->duration;

            if ($currentMinute >= Timeslots::MINUTES_MAX) {
                $currentHour++;
                $currentMinute -= Timeslots::MINUTES_MAX;
            }

            $rangeEnd = implode(':', [$currentHour, $currentMinute < 10 ? '0'. $currentMinute : $currentMinute]);

            $currentMinute += $this->offset;

            $builder[] = [$rangeStart, $rangeEnd];
        }

        $this->slots = $builder;
    }

    public function flatten($delimeter = '|')
    {
        $builder = [];
        foreach ($this->slots as $slot) {
            $builder[] = implode($delimeter, $slot);
        }

        return $builder;
    }

    public function getSlots()
    {
        return $this->slots;
    }

    static public function getAvailable($date)
    {
        $a = 10; $b = 23;

        return new Timeslots($a, $b, 30);
    }

    static public function getUnavailable($date)
    {
        $a = 10; $b = 23;

        return new Timeslots($a, $b, 30);
    }
}