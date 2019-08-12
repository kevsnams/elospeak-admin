<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClassroomSchedulePreference extends Model
{
    public $fillable = [
        'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'start_hour', 'start_minute', 'end_hour', 'end_minute', 'start_date'
    ];

    public $appends = [
        'html_attr', 'start_date_human', 'lz_start_time'
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
            'schedule_date' => $this->start_date
        ];
    }

    public function getStartDateHumanAttribute()
    {
        return date('j F Y', strtotime($this->start_date));
    }

    /** [START] All the get Leading Zero attributes of this model */
    public function getLzStartTimeAttribute()
    {
        return $this->lz_start_hour .':'. $this->lz_start_minute;
    }

    public function getLzStartHourAttribute()
    {
        return $this->start_hour <= 9 ? '0'. strval($this->start_hour) : strval($this->start_hour);
    }

    public function getLzStartMinuteAttribute()
    {
        return $this->start_minute <= 9 ? '0'. strval($this->start_minute) : strval($this->start_minute);
    }
    /** [END] */
}
