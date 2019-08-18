<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClassroomSchedulePreference extends Model
{
    public $fillable = [
        'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'
    ];

    public $appends = [
        'monday_array', 'tuesday_array', 'wednesday_array', 'thursday_array', 'friday_array', 'saturday_array', 'sunday_array'
    ];

    public function student()
    {
        return $this->belongsTo('App\Student');
    }

    public function getMondayArrayAttribute()
    {
        return $this->monday ? json_decode($this->monday) : null;
    }

    public function getTuesdayArrayAttribute()
    {
        return $this->tuesday ? json_decode($this->tuesday) : null;
    }

    public function getWednesdayArrayAttribute()
    {
        return $this->wednesday ? json_decode($this->wednesday) : null;
    }

    public function getThursdayArrayAttribute()
    {
        return $this->thursday ? json_decode($this->thursday) : null;
    }

    public function getFridayArrayAttribute()
    {
        return $this->friday ? json_decode($this->friday) : null;
    }

    public function getSaturdayArrayAttribute()
    {
        return $this->saturday ? json_decode($this->saturday) : null;
    }

    public function getSundayArrayAttribute()
    {
        return $this->sunday ? json_decode($this->sunday) : null;
    }
}
