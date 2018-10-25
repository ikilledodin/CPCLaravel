<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyGroupNames extends Model
{
    //
    protected $guarded = ['id'];
    public function company()
    {
    	return $this->belongsTo('App\Company','company_id');
    }
    
    public function usersGroup()
    {
    	return $this->hasMany('App\UserGroup','group_id')->where('company_id',$this->company_id);
    }

    public function scopeCluster($query,$clusterid)
    {
    	return $query->where('cluster_id', $clusterid);
    }
	
    // public scopeCo
}
