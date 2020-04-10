<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    public $table = 'countries';
    public $appends = ['code', 'is_defined'];

    public function getCodeAttribute()
    {
        return $this->code_iso3166_a2;
    }

    public function getIsDefinedAttribute()
    {
        return filled($this->price) && filled($this->price_weekend) && filled($this->currency_code);
    }
}