<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

class Classroom extends Model
{
    CONST STATUS_UNPAID = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DONE = 2;
    const STATUS_CANCELLED = 3;

    const PAYMENT_NUM_WEEKS_CYCLE = 4;
    const PAYMENT_NUM_DAYS_CYCLE = 30;

    public $appends = ['start_raw', 'end_raw'];

    public static function statusArray()
    {
        return [
            self::STATUS_UNPAID => 'Unpaid',
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_DONE => 'Done',
            self::STATUS_CANCELLED => 'Cancelled'
        ];
    }

    public function student()
    {
        return $this->belongsTo('App\Student');
    }

    public function teacher()
    {
        return $this->belongsTo('App\Teacher');
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
        $status = self::statusArray();

        return $status[$this->status];
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
