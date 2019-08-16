<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    const STATUS_ACTIVE = 1;
    const STATUS_DONE = 2;
    const STATUS_CANCELLED = 3;
    
    const CREATE_MAX_PER_MONTH = 20;


    /**
     * ALLOWED_TIME_FRAME_START|END
     * Means the opening and closing time of the whole classes within the day
     * It uses 24Hr format, so..
     * if value is 8, it means 8AM
     * if the value is 15, it means 3PM
     * if the value is 23, it means 11PM
     * and so on..
     */
    const ALLOWED_TIME_FRAME_START = 7;
    const ALLOWED_TIME_FRAME_END = 23;

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
