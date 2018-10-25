<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class UserProfile extends Model
{
    //
    protected $guarded = ['id'];

    public function user() 
    {
    	return $this->belongsTo('App\User')->withDefault([
    		'name' => 'Guest Author',
    	]);
    }

    public function getFullNameAttribute()
    {
      return $this->attributes['first_name'] . ' ' . $this->attributes['last_name'];
    }

    public function setBirthdateAttribute($value)
    {   
        $this->attributes['birthdate'] = Carbon::parse($value)->format('M-d-Y');
    }

    public function getAgeGroupAttribute()
    {
        $agegroup = 0;
        if(!empty($this->attributes['birthdate'])) {
            $dob = Carbon::parse($this->attributes['birthdate']);     
            $today = Carbon::today();   
            $length = $dob->diffInYears($today);
/*
             WHEN TIMESTAMPDIFF(YEAR,  STR_TO_DATE(b.dob,'%b-%d-%Y'), CURDATE()) <= 30 THEN '1'
               ELSE CASE
                   WHEN
                       TIMESTAMPDIFF(YEAR,  STR_TO_DATE(b.dob,'%b-%d-%Y'), CURDATE()) > 30
                           AND TIMESTAMPDIFF(YEAR,  STR_TO_DATE(b.dob,'%b-%d-%Y'), CURDATE()) <= 40
                   THEN
                       '2'
                   ELSE CASE
                       WHEN
                           TIMESTAMPDIFF(YEAR,  STR_TO_DATE(b.dob,'%b-%d-%Y'), CURDATE()) > 40
                               AND TIMESTAMPDIFF(YEAR,  STR_TO_DATE(b.dob,'%b-%d-%Y'), CURDATE()) <= 50
                       THEN
                           '3'
                       ELSE CASE
                           WHEN
                               TIMESTAMPDIFF(YEAR,  STR_TO_DATE(b.dob,'%b-%d-%Y'), CURDATE()) > 50
                                   AND TIMESTAMPDIFF(YEAR,  STR_TO_DATE(b.dob,'%b-%d-%Y'), CURDATE()) <= 60
                           THEN
                               '4'
                           ELSE CASE
                               WHEN
                                   TIMESTAMPDIFF(YEAR,  STR_TO_DATE(b.dob,'%b-%d-%Y'), CURDATE()) > 60
                                       AND TIMESTAMPDIFF(YEAR,  STR_TO_DATE(b.dob,'%b-%d-%Y'), CURDATE()) <= 70
                               THEN
                                   '5'
                               ELSE '6'
                               */
            if($length <= 30) {
                $agegroup = 1;
            } else if($length > 30 && $length <= 40) {
                $agegroup = 2;
            } else if($length > 40 && $length <= 50) {
                $agegroup = 3;
            } else if($length > 50 && $length <= 60) {
                $agegroup = 4;
            } else if($length > 60 && $length <= 70) {
                $agegroup = 5;
            } else {
                $agegroup = 6;
            }

        }
        return $agegroup;
    }
}
