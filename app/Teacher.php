<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    const EDUC_UNDERGRADUATE = 0;
    const EDUC_COLLEGE_GRADUATE = 1;

    public static function getEducationalAttainmentValues()
    {
        return [
            self::EDUC_UNDERGRADUATE => 'Undergraduate',
            self::EDUC_COLLEGE_GRADUATE => 'College Graduate'
        ];
    }
}
