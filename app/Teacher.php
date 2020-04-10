<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    const EDUC_UNDERGRADUATE = 0;
    const EDUC_COLLEGE_GRADUATE = 1;

    public $hidden = ['password'];
    public $appends = ['age', 'birthday_human', 'user_type'];

    public function classrooms()
    {
        return $this->hasMany('App\Classroom');
    }

    public static function getEducationalAttainmentValues()
    {
        return [
            self::EDUC_UNDERGRADUATE => 'Undergraduate',
            self::EDUC_COLLEGE_GRADUATE => 'College Graduate'
        ];
    }

    public function getFullNameAttribute($value)
    {
        return mb_convert_case($value, MB_CASE_TITLE);
    }

    public function getUserTypeAttribute()
    {
        return 'teacher';
    }

    public function getBirthdayHumanAttribute()
    {
        return $this->birthday ? date('j F Y', strtotime($this->birthday)) : null;
    }

    public function getAgeAttribute() {
        return $this->birthday ? idate('Y') - idate('Y', strtotime($this->birthday)) : null;
    }

    public function getEducationalAttainmentValueAttribute()
    {
        return self::getEducationalAttainmentValues()[$this->educational_attainment];
    }
}
