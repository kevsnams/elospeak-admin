<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    public $hidden = ['password'];
    public $appends = ['age', 'balance', 'birthday_human', 'has_classrooms'];

    public function classroomSchedulePreference()
    {
        return $this->hasOne('App\ClassroomSchedulePreference');
    }

    public function transactions()
    {
        return $this->hasMany('App\StudentTransaction');
    }

    public function classrooms()
    {
        return $this->hasMany('App\Classroom');
    }

    public function getFullNameAttribute($value)
    {
        return mb_convert_case($value, MB_CASE_TITLE);
    }

    public function getBirthdayHumanAttribute()
    {
        return date('j F Y', strtotime($this->birthday));
    }

    public function getAgeAttribute() {
        return idate('Y') - idate('Y', strtotime($this->birthday));
    }
}
