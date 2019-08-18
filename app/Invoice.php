<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    public $append = ['status_text'];
    
    const STATUS_PAID = 1;
    const STATUS_UNPAID = 0;

    public function transaction() {
        return $this->belongsTo('App\StudentTransaction');
    }

    public function getStatusTextAttribute()
    {
        if ($this->status === Invoice::STATUS_PAID) {
            return 'Paid';
        } else if ($this->status === Invoice::STATUS_UNPAID) {
            return 'Unpaid';
        } else {
            return 'Unknown';
        }
    }
}
