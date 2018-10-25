<?php

namespace App;
use App\Events\UserRegistered;

// use App\Events\UserDeleted;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Auth\Passwords\CanResetPassword;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use HasApiTokens, HasRoles, Notifiable;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','verified','status',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $dispatchesEvents = [
        'created' => UserRegistered::class,
        // 'deleted' => UserDeleted::class,
    ];

    /*
    public function boot()
    {
        User::created(function($user) {
     
            $token = $user->verificationtoken()->create([
                'token' => bin2hex(random_bytes(32))
            ]);
     
            event(new UserRegistered($user));
        });
    }

    */

    public function AauthAcessToken()
    {
        return $this->hasMany('\App\OauthAccessToken');
    }

    public function thirdpartytokens()
    {
        return $this->hasOne('App\UserThirdpartytoken');
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    public function profile() 
    {
        return $this->hasOne('App\UserProfile')->withDefault([
            'defaultAvatar' => 'default.jpg',
        ]);
    }

    public function group()
    {
        return $this->hasOne('App\UserGroup');
    }

    public function dpms()
    {
        return $this->hasMany('App\DPms');
    }

    public function ddistflors()
    {
        return $this->hasMany('App\DDistfloors');
    }

    public function prefs()
    {
        return $this->hasOne('App\UserPref');
    }

    public function devices()
    {
        return $this->hasMany('App\UserDevice');
    }

    public function dashboardInfo($datestr)
    {   

        

        $retInfo = array('todaySteps'=>0,
                            'todayCalories'=>0,
                                'todayDist'=>0,
                                    'todayFloor'=>0,
                                        'yesterdaySteps'=>0,
                                            'yesterdayCalories'=>0,
                                                'yesterdayDist'=>0,
                                                    'yesterdayFloor'=>0,

                                                    'bestSteps'=>0,
                                                    'bestCalories'=>0,
                                                    'bestDist'=>0,
                                                    'bestFloor'=>0,

                                                    'totalSteps'=>0,
                                                    'totalCalories'=>0,
                                                    'totalDist'=>0,
                                                    'totalFloor'=>0                                                
                                                );
        $today = Carbon::parse($datestr);
        $yesterday = Carbon::parse($datestr)->subDay();
        // $today = Carbon::parse($datestr);
        $todayeod = Carbon::parse($datestr)->endOfDay();
        $ydayeod = Carbon::parse($datestr)->subDay()->endOfDay();
        Log::info('User::dashboardInfo: today: '.$today->toDateTimeString());
        Log::info('User::dashboardInfo: todayeod: '.$todayeod->toDateTimeString());
        Log::info('User::dashboardInfo: yesterday: '.$yesterday->toDateTimeString());
        Log::info('User::dashboardInfo: yesterday eod: '.$ydayeod->toDateTimeString());


        $todaydpms = $this->dpms()->ofdaytotalbetween($today->toDateTimeString(),$todayeod->toDateTimeString())->first();



        $todayddist = $this->ddistflors()->ofdaytotalbetween($today->toDateTimeString(),$todayeod->toDateTimeString())->first();


      


        $startDate = Carbon::parse('2000-01-01');

        $yesdaydpms = $this->dpms()->ofdaytotalbetween($yesterday->toDateTimeString(),$ydayeod->toDateTimeString())->first();
        $yesdayddist = $this->ddistflors()->ofdaytotalbetween($yesterday->toDateTimeString(),$ydayeod->toDateTimeString())->first();


        $rawSteps = $this->dpms()->ofdaytotalbetween($startDate->toDateTimeString(),$ydayeod->toDateTimeString())->get();
      //  $rawddist = $this->ddistflors()->ofdaytotalbetween($startDate->toDateTimeString(),$ydayeod->toDateTimeString())->get(); 


        $rawddist = DDistfloors::where('user_id','39')->get();

       // return $rawSteps;



        $bestdpms = 0;   
        $bestDist = 0;
        $bestFloor = 0;
        $bestCalories = 0;
        


        $totalSteps = 0;  
        $totalDist = 0;      
        $totalFloor = 0;    
        $totalCalories = 0;


        





        foreach($rawSteps as $value)
        {
            $totalSteps += $value->totalsteps;
            $totalCalories += $value->calcalories;
            

            if($value->totalsteps > $bestdpms)
            {
                $bestdpms = $value->totalsteps;
            }

            if($value->calcalories > $bestCalories)
            {
                $bestCalories = $value->calcalories;
            }

        }


        foreach($rawddist as $value)
        {
            $totalDist += $value->distmeters;
            $totalFloor += $value->floorcnt;

            if($value->distmeters > $bestDist)
            {
                $bestDist = $value->distmeters;
            }

            if($value->floorcnt > $bestFloor)
            {
                $bestFloor = $value->floorcnt;
            }

        }
   




        if($todaydpms) {
            Log::info('User::dashboardInfo: todaydpms '.print_r($todaydpms->toArray(),true));
            $retInfo['todaySteps'] = $todaydpms->totalsteps;
            $retInfo['todayCalories'] = number_format($todaydpms->totalcalc,1);
        }


        if($todayddist) {
            $retInfo['todayDist'] = number_format($todayddist->totaldist/1000,1);      // convert to km for now /1000
            $retInfo['todayFloor'] = number_format($todayddist->totalfloors,0);
        }





        if($yesdaydpms) {
            $retInfo['yesterdaySteps'] = $yesdaydpms->totalsteps;
            $retInfo['yesterdayCalories'] = number_format($yesdaydpms->totalcalc,1);
        }


        if ($yesdayddist) {
            $retInfo['yesterdayDist'] = number_format($yesdayddist->totaldist/1000,1);      // convert to km for now /1000
            $retInfo['yesterdayFloor'] = number_format($yesdayddist->totalfloors,0);
        }








        if($bestdpms) {

            $retInfo['bestSteps'] = $bestdpms;
            $retInfo['bestCalories'] = number_format($bestCalories,1);
        }


        if($bestDist) {
            $retInfo['bestDist'] = number_format($bestDist/1000,1);      // convert to km for now /1000
            $retInfo['bestFloor'] = number_format($bestFloor,0);
        }









        if($totalSteps) {
 
            $retInfo['totalSteps'] = $totalSteps;
            $retInfo['totalCalories'] = number_format($totalCalories,1);
        }


        if($totalDist) {
            $retInfo['totalDist'] = number_format($totalDist/1000,1);      // convert to km for now /1000
            $retInfo['totalFloor'] = number_format($totalFloor,0);
        }






        // Log::info('User::dashboardInfo: todaydpms: '.print_r($todaydpms->toArray(),true));
        /*
        $todaydpms = $this->dpms()->ofDaytotal($today->toDateString())->first();
        $yesdaydpms = $this->dpms()->ofDaytotal($yesterday->toDateString())->first();
        $todayddist = $this->ddistflors()->ofDaytotal($today->toDateString())->first();
        $yesdayddist = $this->ddistflors()->ofDaytotal($yesterday->toDateString())->first();
        */
        /*
        $retInfo = array('todaySteps'=>number_format($todaydpms->totalsteps),
                            'todayCalories'=>number_format($todaydpms->totalcalc,1),
                                'todayDist'=>number_format($todayddist->totaldist/1000,1),       // convert to km for now /1000
                                    'todayFloor'=>number_format($todayddist->totalfloors,0),
                                        'yesterdaySteps'=>number_format($yesdaydpms->totalsteps),
                                            'yesterdayCalories'=>number_format($yesdaydpms->totalcalc,1),
                                                'yesterdayDist'=>number_format($yesdayddist->totaldist/1000,1),      //convert to km for now /1000
                                                    'yesterdayFloor'=>number_format($yesdayddist->totalfloors,0),);
                                                    */

        return $retInfo;
        // return $this->dpms()->ofDaytotal('2018-02-08')->first();
    }

    public function verificationtoken()
    {
        return $this->hasOne('App\VerificationTokens');
    }

    public function getmymodevice_array()
    {
        $mymoinfo = array();
        if($mymoinfo = $this->devices()->whereIn('devicetype',['100','101'])->first()->toArray())
        {
            // Log::info('User::getmymodevice: detected devicetype 100/101: ');
            $mymoinfo['serial']  = hmm_devices_decode_serial($mymoinfo['serial']);
            // Log::info('User::getmymodevice: newserial: '.$newserial);
        }

        return $mymoinfo;
    }

    public function gethrmdevice_array()
    {
        $hrminfo = array();
        if($hrminfo = $this->devices()->where('devicetype','120')->first()->toArray())
        {
            // Log::info('User::getmymodevice: detected devicetype 100/101: ');
            $hrminfo['serial'] = hmm_devices_decode_serial_hrm($hrminfo['serial']);
            // Log::info('User::getmymodevice: newserial: '.$newserial);
        }

        return $hrminfo;
    }

    public function hasVerifiedEmail()
    {
        return $this->verified;
    }

    public function missingdatesgenerator($existarray,$from,$to,$tabletype = 1) 
    {
        if($tabletype == 1) {
            $table1 = 'steps';
            $table2 = 'calories';
        } else {
            $table1 = 'distmeters';
            $table2 = 'floorcnt';
        }
        return  DB::table(DB::raw("(SELECT 
                    a.Date 'datestr'
                from
                    (select 
                        curdate() - INTERVAL (a.a + (10 * b.a) + (100 * c.a)) DAY as Date
                    from
                        (select 0 as a union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) as a
                    cross join (select 0 as a union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) as b
                    cross join (select 0 as a union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) as c) a
                WHERE
                    a.Date between '".$from."' and '".$to."') table1"))->whereNotIn('datestr',$existarray)->selectRaw("table1.datestr,0 '".$table1."',0 '".$table2."',null 'device'")->get()->toArray();
    }

    public function dpms_completedata($to_date,$dayspan = 7)
    {
    // $today = Carbon::now()->toDateString()
    Log::info('User::dpm_completedata: to_date: '.$to_date);
    Log::info('User::dpm_completedata: dayspan: '.$dayspan);
    // $today = Carbon::parse(date_format($to_date,'d/m/Y H:i:s'));
    // $today = Carbon::parse($to_date)->format('Y-m-d');
    $today = Carbon::parse($to_date);
    $to_str = $today->toDateString();
    // $yesterday = $today->subDay();
    $from_str = $today->subDays($dayspan)->toDateString();
    Log::info('User::dpm_completedata: today: '.$to_str);
    // Log::info('User::dpm_completedata: yesterday: '.$yesterday->toDateString());
    Log::info('User::dpm_completedata: lastweek: '.$from_str);
    $last7days = $this->dpms()->ofdaytotalbetween($from_str,$to_str)->get();
/*
    $last7days = $this->dpms()->selectRaw("ifnull(sum(numberofsteps), 0) 'steps',
                    ifnull(sum(calcalories), 0) 'calories',
                    date(datetimestamp) 'datestr',
                    CASE
                        WHEN serialnumber = '666974626974' THEN 'FITBIT'
                        ELSE CASE
                            WHEN serialnumber = '7065646f6d657465720d' THEN 'HEALTHKIT'
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
                    END AS device")->whereBetween('datetimestamp',[$datefrom->toDateString(),$today->toDateString()])->groupby(DB::raw('date(datetimestamp)'),'serialnumber')->get();
                    */
    $existarray = $last7days->pluck('datestr')->all();
    $last7daysarr =$last7days->toArray();
    $missingpm_dates = $this->missingdatesgenerator($existarray,$from_str,$to_str);
   
    $convertedarr = array();
    foreach ($missingpm_dates as $amissing) {
        # code...
        $newarray = array();
        foreach ($amissing as $key => $value) {
            # code...
            $newarray[$key]=$value;
        }
        array_push($convertedarr,$newarray);
    }
    // return $convertedarr;
    // return json_encode($missingdatesarr,true);
    // $last7daysarr = $last7days->all();
    $last7daysarr = array_merge($last7daysarr, $convertedarr);

    return $sorted = array_values(array_sort($last7daysarr, function ($value) {
        return $value['datestr'];
    }));
    }

    public function ddistfloor_completedata($to_date,$dayspan = 7)
    {
    // $today = Carbon::now()->toDateString()
    Log::info('User::dpm_completedata: to_date: '.$to_date);
    Log::info('User::dpm_completedata: dayspan: '.$dayspan);
    // $today = Carbon::parse(date_format($to_date,'d/m/Y H:i:s'));
    // $today = Carbon::parse($to_date)->format('Y-m-d');
    $today = Carbon::parse($to_date);
    $to_str = $today->toDateString();
    // $yesterday = $today->subDay();
    $from_str = $today->subDays($dayspan)->toDateString();
    Log::info('User::dpm_completedata: today: '.$to_str);
    // Log::info('User::dpm_completedata: yesterday: '.$yesterday->toDateString());
    Log::info('User::dpm_completedata: lastweek: '.$from_str);
    $last7days = $this->ddistflors()->ofdaytotalbetween($from_str,$to_str)->get();
/*
    $last7days = $this->dpms()->selectRaw("ifnull(sum(numberofsteps), 0) 'steps',
                    ifnull(sum(calcalories), 0) 'calories',
                    date(datetimestamp) 'datestr',
                    CASE
                        WHEN serialnumber = '666974626974' THEN 'FITBIT'
                        ELSE CASE
                            WHEN serialnumber = '7065646f6d657465720d' THEN 'HEALTHKIT'
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
                    END AS device")->whereBetween('datetimestamp',[$datefrom->toDateString(),$today->toDateString()])->groupby(DB::raw('date(datetimestamp)'),'serialnumber')->get();
                    */
    $existarray = $last7days->pluck('datestr')->all();
    $last7daysarr =$last7days->toArray();
    $missingpm_dates = $this->missingdatesgenerator($existarray,$from_str,$to_str,2);
    $convertedarr = array();
    foreach ($missingpm_dates as $amissing) {
        # code...
        $newarray = array();
        foreach ($amissing as $key => $value) {
            # code...
            $newarray[$key]=$value;
        }
        array_push($convertedarr,$newarray);
    }
    // return $convertedarr;
    // return json_encode($missingdatesarr,true);
    // $last7daysarr = $last7days->all();
    $last7daysarr = array_merge($last7daysarr, $convertedarr);

    return $sorted = array_values(array_sort($last7daysarr, function ($value) {
        return $value['datestr'];
    }));
    }
}
