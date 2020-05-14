<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
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
}
