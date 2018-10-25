<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyPrefs extends Model
{
    //
    protected $guarded = ['id'];
    
    public function companycode() 
    {
    	return $this->belongsTo('App\CompanyCode');
    }
}
