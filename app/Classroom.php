<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    CONST STATUS_UNPAID = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DONE = 2;
    const STATUS_CANCELLED = 3;

    // 4 WEEKS in 1 payment cycle
    const PAYMENT_NUM_WEEKS_CYCLE = 4;

    const CREATE_MAX_PER_MONTH = 20;

    public static function statusArray()
    {
        return [
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
}
