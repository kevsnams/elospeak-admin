<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    const EDUC_GRADUATE = 1;
    const EDUC_UNDERGRADUATE = 0;

    protected $appends = [ 'educational_attainment_label' ];
    protected $guarded = [ 'last_active' ];
    protected $dates = [ 'birthday' ];

    public function classrooms()
    {
        return $this->hasMany('App\Classroom');
    }

    public function getAgeAttribute()
    {
        return idate('Y') - intval($this->birthday->format('Y'));
    }

    public function getEducationalAttainmentLabelAttribute()
    {
        static $educationalAttainments;

        if (!$educationalAttainments) {
            $educationalAttainments = [
                Teacher::EDUC_GRADUATE => 'Graduate',
                Teacher::EDUC_UNDERGRADUATE => 'Undergraduate'
            ];
        }

        return $educationalAttainments[$this->educational_attainment];
    }
}
