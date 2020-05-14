<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    const STATUS_ACTIVE = 1;
    const STATUS_DONE = 0;
    const STATUS_CANCELLED = 2;

    protected $dates = [ 'start', 'end' ];

    public function student()
    {
        return $this->belongsTo('App\Student');
    }

    public function teacher()
    {
        return $this->belongsTo('App\Teacher');
    }

    public function getDurationAttribute()
    {
        return $this->end->diffInMinutes($this->start);
    }
}
