<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Newsfeed extends Model
{
    //
    protected $guarded = ['id'];
    
    public function company()
    {
    	return $this->belongsTo('App\Company');
    }
}
