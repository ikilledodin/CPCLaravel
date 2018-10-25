<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyCode extends Model
{
    //
    protected $guarded = ['id'];

    public function company() 
    {
    	return $this->belongsTo('App\Company');
    }

    public function scopeCode($query,$code)
    {
    	return $query->where('company_code', $code);
    }

    public function prefs() 
    {
        return $this->hasOne('App\CompanyPrefs','company_code');
    }
}
