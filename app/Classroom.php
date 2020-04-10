<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

class Classroom extends Model
{
    const STATUS_ACTIVE = 1;
    const STATUS_DONE = 2;
    const STATUS_CANCELLED = 3;

    public $appends = ['start_raw', 'end_raw'];

    public static function status()
    {
        return collect([
            [self::STATUS_ACTIVE, 'Active'],
            [self::STATUS_DONE, 'Done'],
            [self::STATUS_CANCELLED, 'Cancelled']
        ]);
    }

    public function student()
    {
        return $this->belongsTo('App\Student');
    }

    public function teacher()
    {
        return $this->belongsTo('App\Teacher');
    }

    public function enrollment()
    {
        return $this->belongsTo('App\Enrollment', 'enrollment_id');
    }
    
    public function getStartAttribute($value)
    {
        return new Carbon($value);
    }

    public function getEndAttribute($value)
    {
        return new Carbon($value);
    }

    public function getStatusTextAttribute()
    {
        $status = self::status()->toArray();

        return $status[$this->status][1];
    }

    public function getStartRawAttribute()
    {
        return $this->start->format('Y-m-d H:i:s');
    }

    public function getEndRawAttribute()
    {
        return $this->end->format('Y-m-d H:i:s');
    }
}
