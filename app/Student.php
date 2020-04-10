<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Enrollment;

class Student extends Model
{
    public $hidden = ['password'];
    public $appends = ['age', 'birthday_human', 'user_type', 'has_active_enrollment'];

    public function country()
    {
        return $this->hasOne('App\Country', 'code_iso3166_a2', 'country_code');
    }

    public function enrollments()
    {
        return $this->hasMany('App\Enrollment', 'student_id');
    }

    public function classrooms()
    {
        return $this->hasMany('App\Classroom');
    }

    public function transactions()
    {
        return $this->hasMany('App\StudentTransaction');
    }

    public function getHasActiveEnrollmentAttribute()
    {
        if ($this->enrollments->count() > 0) {
            $have = $this->enrollments->first(function ($value) {
                return $value['active'] == Enrollment::ACTIVE;
            });

            return !is_null($have);
        }

        return false;
    }

    public function getUserTypeAttribute()
    {
        return 'student';
    }

    public function getFullNameAttribute($value)
    {
        return mb_convert_case($value, MB_CASE_TITLE);
    }

    public function getBirthdayHumanAttribute()
    {
        return $this->birthday ? date('j F Y', strtotime($this->birthday)) : null;
    }

    public function getAgeAttribute() {
        return $this->birthday ? idate('Y') - idate('Y', strtotime($this->birthday)) : null;
    }
}
