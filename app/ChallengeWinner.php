<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChallengeWinner extends Model
{
    //
    protected $guarded = ['id'];

    public function corpchallenge() 
    {
    	return $this->belongsTo('App\Corpchallenge','challenge_id');
    	// return $this->belongsTo('App\Company')->wherePivot('account_valid',1);
    }
}
