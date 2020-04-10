<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

class Enrollment extends Model
{
    public $hidden = ['pdf_path'];
    public $appends = ['period'];

    const ACTIVE = 1;
    const INACTIVE = 0;
    
    const PAID = 0;
    const UNPAID = 1;

    public function student()
    {
        return $this->hasOne('App\Student', 'student_id');
    }
    
    public function classrooms()
    {
        return $this->hasMany('App\Classroom', 'enrollment_id');
    }

    public function getPeriodAttribute()
    {
        if ($this->classrooms->count()) {
            $sorted = $this->classrooms->sortBy(function ($classroom) {
                return $classroom->created_at;
            })->values();

            $start = new Carbon($sorted->first()->start);
            $end = new Carbon($sorted->last()->end);

            return [$start->format('F j, Y h:i A'), $end->format('F j, Y h:i A')];
        }

        return null;
    }
}
