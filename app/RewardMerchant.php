<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RewardMerchant extends Model
{
    //
    protected $guarded = ['id'];


    public function companies()
    {
    	return $this->hasManyThrough('App\Company','App\CompanyMerchantReward','company_id','id','id','company_id');
    }
}
