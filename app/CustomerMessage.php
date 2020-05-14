<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerMessage extends Model
{
    const IS_READ = 1;
    const IS_UNREAD = 0;
}
