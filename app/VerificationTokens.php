<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VerificationTokens extends Model
{
    //
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'token',
    ];

    public function user() 
    {
    	return $this->belongsTo('App\User');
    }

    /**
	 * Get the route key for the model.
	 *
	 * @return string
	 */
	public function getRouteKeyName()
	{
	    return 'token';
	}
}
