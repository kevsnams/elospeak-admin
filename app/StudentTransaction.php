<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentTransaction extends Model
{
    public function student()
    {
        return $this->belongsTo('App\Student');
    }
    public function invoice()
    {
        return $this->belongsTo('App\Invoice');
    }
}
