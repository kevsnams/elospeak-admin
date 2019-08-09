<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClassroomSchedulePreference extends Model
{
    public $fillable = [
        'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'start_hour', 'start_minute', 'end_hour', 'end_minute', 'start_date'
    ];

    public $appends = [
        'html_attr'
    ];

    public function student()
    {
        return $this->belongsTo('App\Student');
    }

    public function getHtmlAttrAttribute()
    {
        $scheduleDays = [
            'M' => (bool) $this->monday,
            'T' => (bool) $this->tuesday,
            'W' => (bool) $this->wednesday,
            'Th' => (bool) $this->thursday,
            'F' => (bool) $this->friday,
            'S' => (bool) $this->saturday
        ];

        return [
            'schedule_days' => $scheduleDays,
            'schedule_start_time' => $this->lz_start_time,
            'schedule_end_time' => $this->lz_end_time,
            'schedule_date' => $this->start_date
        ];
    }

    /** [START] All the get Leading Zero attributes of this model */
    public function getLzStartTimeAttribute()
    {
        return $this->lz_start_hour .':'. $this->lz_start_minute;
    }

    public function getLzEndTimeAttribute()
    {
        return $this->lz_end_hour .':'. $this->lz_end_minute;
    }

    public function getLzStartHourAttribute()
    {
        return $this->start_hour <= 9 ? '0'. strval($this->start_hour) : strval($this->start_hour);
    }

    public function getLzStartMinuteAttribute()
    {
        return $this->start_minute <= 9 ? '0'. strval($this->start_minute) : strval($this->start_minute);
    }

    public function getLzEndHourAttribute()
    {
        return $this->end_hour <= 9 ? '0'. strval($this->end_hour) : strval($this->end_hour);
    }

    public function getLzEndMinuteAttribute()
    {
        return $this->end_minute <= 9 ? '0'. strval($this->end_minute) : strval($this->end_minute);
    }
    /** [END] */
}
