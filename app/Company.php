<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use App\DPms;
use App\Corpchallenge;

class Company extends Model
{
    //
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];


    public function code() 
    {
        return $this->hasOne('App\CompanyCode');
    }

    public function validUsers()
    {
    	// return $this->hasMany('App\UserGroup')->as('activeusers')->withTimestamps();
    	// return $this->hasMany('App\UserGroup','company_id')->where('account_valid',1);
    	return $this->hasMany('App\UserGroup')->AccountValid(1);
    	// return $this->hasManyThrough('App\UserGroup', 'App\User');
    }

    public function invalidUsers()
    {
    	// return $this->hasMany('App\UserGroup')->as('activeusers')->withTimestamps();
    	// return $this->hasMany('App\UserGroup','company_id')->where('account_valid',0);
    	return $this->hasMany('App\UserGroup')->AccountValid(0);
    	// return $this->hasManyThrough('App\UserGroup', 'App\User');
    }

    public function reward_merchant()
    {
        return $this->hasOne('App\CompanyMerchantReward','company_id');
    }

    public function allUsers()
    {
    	// return $this->hasMany('App\UserGroup')->as('activeusers')->withTimestamps();
    	return $this->hasMany('App\UserGroup');
    	// return $this->hasManyThrough('App\UserGroup', 'App\User');
    }

    public function corpchallenges()
    {
        return $this->hasMany('App\CorpChallenge');
    }

    public function infostreams()
    {
        return $this->hasMany('App\CorpinformationStream');
    }

    public function events()
    {
        return $this->hasMany('App\Event');
    }

    public function newsfeeds()
    {
        return $this->hasMany('App\NewsFeed');
    }

    public function subgroups()
    {
    	// return $this->hasMany('App\UserGroup')->as('activeusers')->withTimestamps();
    	return $this->hasMany('App\CompanyGroupNames','company_id');
    	// return $this->hasManyThrough('App\UserGroup', 'App\User');
    }

    public function isClusterEnabled()
    {
    	return $this->cluster_mode ? TRUE:FALSE;
    }

    public function maingroups()
    {
    	// return $this->hasMany('App\UserGroup')->as('activeusers')->withTimestamps();
    	return $this->hasMany('App\CompanyClusterNames','company_id');
    	// return $this->hasManyThrough('App\UserGroup', 'App\User');
    }

    public function chatchannels()
    {
        return $this->hasMany('App\CompanyChatChannel','company_id');
    }

    public function users()
    {
    	return $this->hasManyThrough('App\User','App\UserGroup','company_id','id','id','user_id');
    }

    public function programleaderboard($limit=10)
    {
    	return $this->users()->join('d_pms','users.id','d_pms.user_id')->join('user_profiles as t3','users.id','t3.user_id')->selectRaw('users.id as uid,sum(d_pms.numberofsteps) as totalsteps,ROUND(sum(d_pms.calcalories),2) as totalcalories,t3.avatar,CONCAT(t3.first_name,\' \',t3.last_name) as fullname')->whereBetween('d_pms.datetimestamp',[$this->program_startdate,$this->program_enddate])->groupby('uid')->orderby('totalsteps','desc')->limit($limit)->get();
    	
    	/*
    	$query = Company::query();


    	Log::info('Company::leaderboard: datefrom: '.$datefrom.' dateto: '.$dateto.' datatype: '.$datatype);
    	$query->users()->when($datatype == 1, function ($q) {
    		Log::info('Company::leaderboard: executing sum(numberofsteps)');
		    return $q->join('d_pms','users.id','d_pms.user_id')->selectRaw('users.id as uid,sum(d_pms.numberofsteps) as totalcount')->whereBetween('d_pms.datetimestamp',['2017-10-01',NOW()])->groupby('uid')->orderby('totalcount','desc')->limit(10)->get();
		});
		$query->users()->when($datatype == 2, function ($q) {
			Log::info('Company::leaderboard: executing sum(calcalories)');
		    return $q->join('d_pms','users.id','d_pms.user_id')->selectRaw('users.id as uid,sum(d_pms.calcalories) as totalcount')->whereBetween('d_pms.datetimestamp',['2017-10-01',NOW()])->groupby('uid')->orderby('totalcount','desc')->limit(10)->get();
		});
		*/

    }

    public function challengelist($date)
    {
        /*
        $first = DB::table('users')
            ->whereNull('first_name');

        $past = DB::Corpchallenge('users')
            ->whereNull('last_name')
            ->union($first)
            ->get();

            */
        $pastchallenges = $this->corpchallenges()->ofpastchallenges($date);
        $activepastchallenges = $this->corpchallenges()->ofactivechallenges($date)
                                    ->union($pastchallenges)
                                    ->orderby('weight','desc')
                                    ->get();
        return $activepastchallenges;
    }


    public function challengelist2($date)
    {
        $pastchallenges = Corpchallenge::where('company_id','=','2')->get();

        $returndata = array();
        foreach($pastchallenges as $data)
        {

            $rawCategory = $data->challenge_cat;
            $categoryTitle = "";

            switch($rawCategory) {
                case 0://CHALLENGE_OVERALL:
                    $categoryTitle = "Overall Challenge";
                break;
                case 1://CHALLENGE_DAILY:
                    $categoryTitle = "Daily Challenge";
                    $today = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
                //	$today = mktime(0, 0, 0, 1,28, 2015);
                    $midnight = mktime(23, 59, 0, date("m", $today), date("d", $today), date("Y", $today));
                //	$challengestartdate = date('Y M d H:i:s',$today);
                //	$rowenddate = date('Y M d H:i:s',$tomorrow);
                    $challengestartdate = date('Y-m-d H:i:s',$today);
                    $rowenddate = date('Y-m-d H:i:s',$midnight);
                break;
                case 2://CHALLENGE_WEEKLY:
                    $categoryTitle = "Weekly Challenge";
                break;
                case 3://CHALLENGE_INTRADAY:
                    $categoryTitle = "Intraday Challenge";
                break;
                case 4://CHALLENGE_DURATION:
                    $categoryTitle = "Theme Challenge";
                break;
            }





            $adata['id'] = $data->id;
            $adata['title'] = $data->challenge_title;
            $adata['desc'] = $data->challenge_text;
            $adata['imageURL'] = $data->challenge_imageurl;
            $adata['challengeweight'] = $data->weight;      
            
            $adata['category'] = $categoryTitle;

            $adata['startdate'] = $data->startdate;
            $adata['enddate'] = $data->enddate;
            $adata['status'] = "0";
            array_push($returndata,$adata);
        }
        return $returndata;
        
    }
}
