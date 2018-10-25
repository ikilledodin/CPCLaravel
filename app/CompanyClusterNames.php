<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyClusterNames extends Model
{
    //
    protected $guarded = ['id'];
    
    public function company()
    {
    	return $this->belongsTo('App\Company');
    }

    public function subgroups()
    {
    	return $this->hasMany('App\CompanyGroupNames','cluster_id')->where('company_id',$this->company_id);
    }
}
