<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class WebsiteSetting extends Model
{
    public $fillable = ['key', 'value'];

    public function scopeClassrooms($query, $keys = null)
    {
        if (is_array($keys)) {
            $findKeys = array_map(function ($key) {
                return 'CLASSROOM.'. $key;
            }, $keys);

            return $query->whereIn('key', $findKeys);
        } else {
            return $query->where('key', 'LIKE', 'CLASSROOM.%');
        }
    }
}
