<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    const STATUS_PAID = 1;
    const STATUS_UNPAID = 0;
}
