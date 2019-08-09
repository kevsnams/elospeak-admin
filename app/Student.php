<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    public $hidden = ['password'];
    public $append = ['age'];

    public function classroomSchedulePreference()
    {
        return $this->hasOne('App\ClassroomSchedulePreference');
    }

    public function getFullNameAttribute($value)
    {
        return mb_convert_case($value, MB_CASE_TITLE);
    }

    public function getAgeAttribute() {
        return idate('Y') - idate('Y', strtotime($this->birthday));
    }
}
