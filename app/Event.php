<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class Event extends Model
{
    //
    protected $guarded = ['id'];
    
    public function company()
    {
    	return $this->belongsTo('App\Company');
    }

    public function content() 
    {
        return $this->hasOne('App\EventContent');
    }


    public static function getEvents($company_id)
    {
        $query = DB::table('events AS t1')
        ->select(DB::raw('
        t1.id "eventid",
             t1.startdatetime "event_startdate",
             t1.enddatetime "event_enddate",
             t2.event_title "event_name",
             t2.event_en_body "en_text",
             t2.event_ar_body "ar_text",
             t2.ar_exist "arAvailable",
             t2.invite_url "invitation_url",
             t2.cover_url "coverimage_url",
             t2.location "event_location"
        '))
        ->join('event_contents AS t2','t1.id','=','t2.event_id')
        ->where('t1.company_id','=',$company_id)
        ->get();
        return $query;



        
    }

}
