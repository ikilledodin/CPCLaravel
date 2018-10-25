<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Points extends Model
{
    //

    protected $table = 'points';



    public static function currentProgramEarned($userid,$startdate)
    {


    $query = DB::select(DB::raw('
                SELECT ifnull(sum(points),0) "earned"
                from points 
                where user_id= "$userid"
                and Type <> 50 
                and date(DateTimestamp) >= "$startdate"
                '));
 
/*
        $query = DB::table('points')
                ->select(DB::raw('ifnull(sum(points),0) AS "earned"'))
                ->where('user_id','= ',$userid)
                ->where('Type','<>','50')
                ->where('DateTimestamp','>=',$startdate)
                ->get();

*/

    return $query;


    }

    public static function donatedPoints($userid,$strstartdate)
    {
        $query = DB::select(DB::raw('
                SELECT ifnull(sum(points),0) "donatedpts" 
                from points 
                where user_id='.$userid.'
                and Type <> 50 
                and date(DateTimestamp) >= '.$strstartdate.' 
                and DATE_FORMAT(DateTimestamp,"%M %Y") = "October 2016"
                '));
               // -get();
        return $query;
    }

    public static function transferedResults($userid,$strstartdate)
    {
        $query = DB::select(DB::raw('
        SELECT 
        t1.uid,
        ifnull(sum(miles),0) "transferred"
        from
        drupal.convertedmiles t1,
        (SELECT 
            id, datetimestamp, points "ptsMiles"
        from
            hmm.points
        where
            userid ='.$userid.' and type <> 50
                and date(datetimestamp) >= '.$strstartdate.'
                and DATE_FORMAT(datetimestamp, "%M %Y") <> "October 2016"
        order by datetimestamp) t2
        where
        t1.pointsid = t2.id and t1.status = 1
        '));//-get();

        return $query;
    }

    public static function userpointsinfo($userid,$strstartdate,$strenddate)
    {
        $query = DB::select(DB::raw('        
            SELECT 
                Points,
                date(DateTimestamp) "ptsdate",
                Type "code"
            from 
                points 
            where 
                user_id='.$userid.' 
                and Type<>50 
                and date(DateTimestamp) >= '.$strstartdate.' 
                and date(DateTimestamp) <= '.$strenddate.' 
            ORDER BY 
                DateTimestamp
        '));//-get();

        return $query();
    }

  

}
 