<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DDistfloors extends Model
{
    //
    protected $guarded = ['id'];
    public function user() 
    {
    	return $this->belongsTo('App\User');
    }

    public function scopeOfDate($query,$datestr)
    {
    	return $query->whereDate('datetimestamp',$datestr);
    	// return $this->whereDate('datetimestamp',$datestr)->orderBy('datetimestamp','desc')->get();
    	// return $this;
    }

    public function scopeOfDatetime($query,$datestr)
    {
        return $query->where('datetimestamp',$datestr);
    }

    public function scopeOfBetween($query,$datefrom,$dateto) 
    {
    	return $query->whereBetween('datetimestamp', array($datefrom, $dateto));
    }
    
    public function scopeOfdaytotal($query,$datestr) 
    {
    	// $datatype 
    	// 1 - STEPS
    	// 2 - CALORIES
    	
    	// Log::info('DPms::scopeOfdaytotal: datestr: '.$datestr);
    	$query = $query->whereDate('datetimestamp',$datestr)->selectRaw('user_id, ifnull(sum(distmeters),0) as totaldist, ifnull(sum(floorcnt),0) as totalfloors');

    	/*
    	$query = $query->whereDate('datetimestamp',$datestr);
		$query->when($datatype == 1, function ($q) {
		    return $q->selectRaw('user_id, sum(numberofsteps) as totalsteps');
		});

		$query->when(request('filter_by') == 'date', function ($q) {
		    return $q->orderBy('created_at', request('ordering_rule', 'desc'));
		});
		*/
    }
    public function scopeOfdaytotalBetween($query,$datefrom,$dateto)
    {
        // $query = $query->whereDate('datetimestamp',$datestr)->selectRaw('user_id, ifnull(sum(numberofsteps),0) as totalsteps, ifnull(sum(calcalories),0) as totalcalc');
        $query = $query->selectRaw("ifnull(sum(distmeters), 0) 'totaldist',
                    ifnull(sum(floorcnt), 0) 'totalfloors',
                    date(datetimestamp) 'datestr',
                    CASE
                        WHEN serialnumber = '666974626974' THEN 'FITBIT'
                        ELSE CASE
                            WHEN serialnumber like '7065646f6d65746%' THEN 'HEALTHKIT'
                            ELSE CASE
                                WHEN serialnumber = '676f6f676c65666974' THEN 'GOOGLEFIT'
                                ELSE CASE
                                    WHEN serialnumber = '4741524d494e' THEN 'GARMIN'
                                    ELSE CASE
                                        WHEN serialnumber = '534845414c5448' THEN 'SHEALTH'
                                        ELSE CASE
                                            WHEN serialnumber = '6a6177626f6e65' THEN 'JAWBONE'
                                            ELSE 'MYMO'
                                        END
                                    END
                                END
                            END
                        END
                    END AS device")->whereBetween('datetimestamp',[$datefrom,$dateto])->groupby(DB::raw('date(datetimestamp)'),'serialnumber');//->get();
    }
}
