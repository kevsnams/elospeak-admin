<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    const EDUC_UNDERGRADUATE = 0;
    const EDUC_COLLEGE_GRADUATE = 1;

    public $hidden = [
        'password'
    ];

    public static function getEducationalAttainmentValues()
    {
        return [
            self::EDUC_UNDERGRADUATE => 'Undergraduate',
            self::EDUC_COLLEGE_GRADUATE => 'College Graduate'
        ];
    }

    public function getFullNameAttribute($value)
    {
        return ucwords($value);
    }

    public function getAgeAttribute() {
        return idate('Y') - idate('Y', strtotime($this->birthday));
    }

    public function getEducationalAttainmentValueAttribute()
    {
        return self::getEducationalAttainmentValues()[$this->educational_attainment];
    }
}
