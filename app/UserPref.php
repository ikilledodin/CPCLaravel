<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserPref extends Model
{
    //
    protected $guarded = ['id'];

    public function user()
    {
    	return $this->belongsTo('App\User');
    }

    public function getUnserializeAttribute()
    {
    	return unserialize($this->value);
    }

    public function saveserialize($prefs_array)
    {
    	$this->value = serialize($prefs_array);
    }

    public function scopeOfdailygoal($query) 
    {
    	return $query->where('type','dailygoal');
    }
}
