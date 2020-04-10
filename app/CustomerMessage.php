<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class CustomerMessage extends Model
{
    public $appends = ['sent_date'];

    const READ = 1;
    const UNREAD = 0;
    
    public function getSentDateAttribute()
    {
        return $this->created_at->format('F j, Y h:i A');
    }
}
