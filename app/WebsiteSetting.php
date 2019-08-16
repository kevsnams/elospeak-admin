<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WebsiteSetting extends Model
{
    public $fillable = ['key', 'value'];

    public function scopeClassrooms($query)
    {
        return $query->where('key', 'LIKE', 'CLASSROOM.%');
    }
}
