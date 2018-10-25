<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserGroup extends Model
{
    //
    protected $guarded = ['id'];

    public function company() 
    {
    	return $this->belongsTo('App\Company','company_id');
    	// return $this->belongsTo('App\Company')->wherePivot('account_valid',1);
    }

    public function user() 
    {
    	return $this->belongsTo('App\User','user_id')->withTimestamps();
    }

    public function scopeOf($query,$datefrom,$dateto) 
    {
    	return $query->whereBetween('datetimestamp', array($datefrom, $dateto));
    }

    public function scopeAccountValid($query, $type)
    {
        return $query->where('account_valid', $type);
    }

    public function groupname()
    {
    	// return $this->belongsTo(CompanyGroupNames::class)->withPivot('company_id');
    	return $this->belongsTo('App\CompanyGroupNames','group_id');
    }

    public function clustername()
    {
    	// return $this->belongsTo(CompanyGroupNames::class)->withPivot('company_id');
    	return $this->belongsTo('App\CompanyClusterNames','cluster_id');
    }

}
