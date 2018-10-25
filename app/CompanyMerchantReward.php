<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyMerchantReward extends Model
{
    //
    protected $guarded = ['id'];

    public function merchantinfo()
    {
    	return $this->belongsTo('App\RewardMerchant','merchant_id');
    }

    public function conversion_table()
    {
    	return $this->hasMany('App\CompanyRewardConv','company_id')->where('merchant_id',$this->merchant_id);
    }
}
