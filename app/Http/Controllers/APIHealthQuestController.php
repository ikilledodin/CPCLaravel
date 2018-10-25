<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Hash;
use Config;
use Carbon\Carbon;

use App\Company;
use App\CompanyCode;
use App\CompanyMerchantReward;
use App\Corpchallenge;
use App\User;
use App\UserGroup;
use App\UserProfile;

use App\EventContent;
use App\Event;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class APIHealthQuestController extends Controller
{
   

    /**
    * Get a validator for an incoming Todo request.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  $type
    * @return \Illuminate\Contracts\Validation\Validator
    */
 
   public function validations($request,$type)
   {
        $errors = [];
        $error = false;

        if($type == "company leaderboard"){
            $validator = Validator::make($request->all(),[
                'user_id' => 'required',
                'company_id' => 'required',
            ]);

            if($validator->fails()){
                   $error = true;
                   $errors = $validator->errors();

            } 
        }
        else if($type == "company config"){
            $validator = Validator::make($request->all(),[
                'companycode' => 'required',
            ]);

            if($validator->fails()){
                   $error = true;
                   $errors = $validator->errors();

            } 
        }
        else if($type == "company challengelist"){
            $validator = Validator::make($request->all(),[
                'date' => 'required|date_format:Y-m-d',
                'company_id' => 'required',
            ]);

            if($validator->fails()){
                   $error = true;
                   $errors = $validator->errors();

            } 
        }
        else if($type == "company individual_leaderboard"){
            $validator = Validator::make($request->all(),[
                'date' => 'required|date_format:Y-m-d',
                'company_id' => 'required',
                'challenge_id' => 'required',
            ]);

            if($validator->fails()){
                   $error = true;
                   $errors = $validator->errors();

            } 
        }
        

        return ["error" => $error,"errors"=>$errors];
   }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       // 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function companyleaderboard(Request $request) 
    {
        $error = $this->validations($request,"company leaderboard");
        if($error['error']) 
        {
            // return $this->prepareResult(false, [], $error['errors'],"error in updating data");
            return tupe_prepareResult(false, [], $error['errors'],"missing parameters");
        } 
        else 
        {
            $leaderboard = array();
            if(User::find($request['user_id'])->group->company_id == $request['company_id']) {

                $leaderboard = User::find($request['user_id'])->group->company->programleaderboard();
                // $companyinfo = User::find(Auth::user()->id)->group->company;
                // $userinfo = User::find(Auth::user()->id);
                // $profileinfo = User::find(Auth::user()->id)->profile;
                // $email = $userinfo->email;
                // $fullname = $profileinfo->fullname;
                $finalarray = array();
                foreach ($leaderboard as $aleaderboard) {
                    # code...
                    $newpath = $aleaderboard['avatar'] ? asset('storage').'/'.$aleaderboard['avatar'] : asset('storage').'/'.'avatars/avatar.png';
                    $aleaderboard['avatar'] = $newpath;
                    array_push($finalarray, $aleaderboard);
                }
                $response['leaderboard'] = $finalarray;
                return tupe_prepareResult(true, $response,[],"success");
            } 
            else 
            {
                return tupe_prepareResult(false, [], "unauthorized","You are not authorized to view this leaderboard.");
            }
        
            // $ticket = $ticket->fill($request->all())->save();
            // return $this->prepareResult(true, $ticket, $error['errors'],"updating data");

        }
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function companychallenges(Request $request) 
    {
        $error = $this->validations($request,"company challengelist");
        if($error['error']) 
        {
            // return $this->prepareResult(false, [], $error['errors'],"error in updating data");
            return tupe_prepareResult(false, [], $error['errors'],"missing parameters");
        } 
        else 
        {
            $leaderboard = array();
            if(User::find(Auth::user()->id)->group->company_id == $request['company_id']) {
                $companyinfo = Company::find($request['company_id']);
                $challengelist = $companyinfo->challengelist($request['date']);

                $ret = array();
                $challengescount = $challengelist->count();
                $ret["challengecount"] = $challengescount;
                $origdate = Carbon::parse($request['date']);
                if($challengelist) {
                    $challenges = array();
                    
                    foreach($challengelist as $row) {
                        $id = $row->id;
                        $title = $row->challenge_header;
                        $desc = $row->challenge_text;
                        $url = $row->challenge_imageurl ? asset('storage').'/'.$row->challenge_imageurl : asset('storage').'/'.'companies/default/defaultchallengeimg.png';
                        // $url = $base_url . '/'.$row['challenge_imageurl'];
                        $challengeweight = $row->weight;
                        $category = $row->challenge_cat;
                        $categorystr = "";
                        $challengestartdate = $row->startdate;
                        $rowenddate = $row->enddate;
                        $challengendateCarbon = Carbon::parse($row->enddate);
                        $challengenddate = strtotime($rowenddate);
                        $status = 1;
                        switch($category) {
                            case Config::get('constants.challenges.CHALLENGE_OVERALL'):
                                $categorystr = "Overall Challenge";
                            break;
                            case Config::get('constants.challenges.CHALLENGE_DAILY'):
                                $categorystr = "Daily Challenge";
                                // $today = Carbon::today()->toDateString();
                                // $today = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
                            //  $today = mktime(0, 0, 0, 1,28, 2015);
                                // $midnight = mktime(23, 59, 0, date("m", $today), date("d", $today), date("Y", $today));
                                // $midnight = Carbon::now()->endOfDay()->toDateString();
                            //  $challengestartdate = date('Y M d H:i:s',$today);
                            //  $rowenddate = date('Y M d H:i:s',$tomorrow);
                                // $challengestartdate = date('Y-m-d H:i:s',$today);
                                $challengestartdate = Carbon::today()->toDateTimeString();
                                // $rowenddate = date('Y-m-d H:i:s',$midnight);
                                $rowenddate = Carbon::now()->endOfDay()->toDateTimeString();
                            break;
                            case Config::get('constants.challenges.CHALLENGE_WEEKLY'):
                                $categorystr = "Weekly Challenge";
                            break;
                            case Config::get('constants.challenges.CHALLENGE_INTRADAY'):
                                $categorystr = "Intraday Challenge";
                            break;
                            case Config::get('constants.challenges.CHALLENGE_DURATION'):
                                $categorystr = "Theme Challenge";
                            break;
                        }
                        
                        if($challengendateCarbon->lt($origdate)) {
                            $status = 0;
                        }
                        $challenges[] = array('id'=>$id,'title'=>$title,'desc'=>$desc,'imageURL'=> $url,'challengeweight'=>$challengeweight,'category'=>$categorystr,'startdate'=>$challengestartdate,'enddate'=>$rowenddate,'status'=>$status);
                    }
                } 
                
                /*
                if(count($challenges) > 0) {
                    $ret["challenges"] = $challenges;
                }
                */
                $ret["challenges"] = $challenges;
                return tupe_prepareResult(true, $ret,[],"success");
            } 
            else 
            {
                return tupe_prepareResult(false, [], "unauthorized","You are not authorized to view this resource.");
            }
        
            // $ticket = $ticket->fill($request->all())->save();
            // return $this->prepareResult(true, $ticket, $error['errors'],"updating data");

        }
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function companyconfig(Request $request) 
    {
        $error = $this->validations($request,"company config");
        if($error['error']) 
        {
            // return $this->prepareResult(false, [], $error['errors'],"error in updating data");
            return tupe_prepareResult(false, [], $error['errors'],"missing parameters");
        } 
        else 
        {
            $leaderboard = array();
            // if(User::find(Auth::user()->id)->group->company->code->company_code == $request['companycode']) {
            // if(CompanyCode::where('company_code',$request['companycode'])->exists()) {
            if($ccode = CompanyCode::where('company_code', $request['companycode'])->first()) {
                // $leaderboard = User::find($request['user_id'])->group->company->programleaderboard();

                // $companyinfo = User::find(Auth::user()->id)->group->company;
                // $userinfo = User::find(Auth::user()->id);
                // $profileinfo = User::find(Auth::user()->id)->profile;
                // $email = $userinfo->email;
                // $fullname = $profileinfo->fullname;
                $devicesupport = array();
                $myweightinfo = array();
                $featuresarr = array();
                $groupings = array();
                $splashObj = array();
                $rewardsinfo = array();
                $prefsdefault = 'a:1:{s:8:"distance";s:2:"mi";}';
                $company = Company::find($ccode->company_id);
                $company_code = $company->code;
                $company_code['comp_logo'] = $newpath = $company_code['comp_logo'] ? asset('storage').'/'.$company_code['comp_logo'] : asset('storage').'/'.'companies/default/logo.png';
                $company['devicesupport'] = tupe_analyzedevsupport($company_code['device_support']);
                $company['features'] = $featuresarr = tupe_analyzefeatures($company_code['feature_enable']);

                if(isset($company['features'][4]['value']) ) {
                    $myweight = $company['features'][4]['value'];
                    $myweightinfo['menu_title'] = 'My Weight';
                    if($myweight==1) 
                        $myweightinfo['addButtonShow'] = 1;
                    else 
                        $myweightinfo['addButtonShow'] = 0;
                }
                $company['myweightinfo'] = $myweightinfo;
        
                $company['actdataShow'] = tupe_analyzedatashow($company_code['datashow']);
                $company['myweightinfo'] = $myweightinfo;
                if(!empty($company_code->prefs->prefs_serialized)) {
                    $prefsarr = unserialize($company_code->prefs->prefs_serialized);
                } else {
                    $prefsarr = unserialize($prefsdefault);
                }
                $company['companyPrefs'] = $prefsarr;
                $groupings = $this->getCompanyGroupings($company['id']);
                $company['groupings'] = $groupings;
                $splashObj = $this->getCompanySplashScreen($company_code['app_splash_screen']);
                $company['splash_screen_urls'] = $splashObj;
                $company['channels'] = $company->chatchannels()->get();
                $rewardsinfo = $this->getCompanyRewardsInfo($company['id']);
                $company['rewardsinfo'] = $rewardsinfo;
                // add 20 days as grace period for program_enddate
                $company['expiry'] = Carbon::createFromTimeString($company['program_enddate'], $company['tzname'])->addDays(20)->toDateTimeString();

                $response['company'] = $company;
                return tupe_prepareResult(true, $response, $error['errors'],"success");
            } 
            else 
            {
                return tupe_prepareResult(false, [], "Company code error","Company code does not exist.");
            }
        
            // $ticket = $ticket->fill($request->all())->save();
            // return $this->prepareResult(true, $ticket, $error['errors'],"updating data");
        }
    }

    public function getCompanyGroupings($company_id)
    {
        

        $ret = array();
    //  $ret["challengecount"] = $challengescount;
        $companygroups = array();
        if($company = Company::find($company_id)) {
            if($company->isClusterEnabled()) {
                $clustercount = 0;
                $companyclusters = $company->maingroups()->get();
                foreach ($companyclusters as $cluster) {
                     $clustergroups = $cluster->subgroups()->get();
                     $clusterinfo = array();
                     $clusterinfo['clustername'] = $cluster['name'];
                     $clusterinfo['clusterid'] = $cluster['id'];
                     $clusterinfo['groups'] = $clustergroups;
                     $companygroups[] = $clusterinfo;
                }
                /*
                foreach ($companyclusters as $cluster) {
                    $thisclustername = $cluster['name'];
                    $thisclusterid = $cluster['id'];
                    $companygroups[$clustercount]['clustername'] = $thisclustername;
                    $companygroups[$clustercount]['clusterid'] = $thisclusterid;
                    $groupcount = 0;
                    $clustergroups = $cluster->subgroups()->get();
                    foreach ($clustergroups as $clustergroup) {
                        $thisgroupid = $clustergroup['id'];
                        $thisclusterid = $clustergroup['cluster_id'];
                        $thisgroupname = $clustergroup['name'];
                        $thisgroupphoto = $clustergroup['photourl'];
                        $companygroups[$clustercount]['groups'][$groupcount] = array('groupid'=>$thisgroupid,'clusterid'=>$thisclusterid,'name'=>$thisgroupname,'photourl'=>$thisgroupphoto);
                        $groupcount++;
                    }
                    $clustercount++;
                }
                */
            } else {
                $companygroups = $company->subgroups()->get();
                // foreach($companygroups as $group) {
                //     $companygroups[] = array('groupid' => $group['id'],'groupname' =>$group['name']);
                // }
            }
        }
        if(count($companygroups) > 0) {
            $ret["companygroup"] = $companygroups;
        }
        $ret["clustermode"] = $company->cluster_mode;
        $ret["groupmode"] = $company->group_mode;
        
        return $ret;
    }

    public function getCompanySplashScreen($splash_screen_dir) 
    {
        $splashobj = array();
        $iOSkeys = array('1x','2x','3x');
        $Androidkeys = array('xxhdpi','xhdpi','hdpi','mdpi');
        
        // $orgsplash = $dbcon->doQuery("SELECT app_splash_screen from drupal.company_code where orgid=$corpid");
        // if($splashdir = mysql_fetch_assoc($orgsplash)) {
        foreach($iOSkeys as $value) {

            $splashobj['iOS'][$value] = asset('storage').'/'.$splash_screen_dir.'ios/'.$value.'/splash.png';
        }
        foreach($Androidkeys as $value) {
            $splashobj['Android'][$value] = asset('storage').'/'.$splash_screen_dir.'android/'.$value.'/splash.png';
        //  $splashobj['Android'][$value] = $base_url .'/'.$splashdir['app_splash_screen'].$value.'.png';
        }
        // }
        return $splashobj;
    }

    public function getCompanyRewardsInfo($company_id) 
    {
        $company_merchant = array();
        if($company_merchant = CompanyMerchantReward::with('merchantinfo')->where('company_id',1)->first()) {
            $conv_table = $company_merchant->conversion_table()->get();
            $company_merchant['tableinfo'] = $conv_table;
            $howitworks = 'The miles you earn from your steps are updated at the end of each day.';
            $helptableheader = 'Etihad Miles Conversion';
            $company_merchant['table_header'] = $helptableheader;
            $company_merchant['hiw_body'] = $howitworks;
            // $helpinfo = array('tableinfo'=>$convtable,'table_header' => $helptableheader,'hiw_body'=>$howitworks);
        }
        /*
        if($company_merchant = CompanyMerchantReward::find($company_id)) {
            if($merchantinfo = $company_merchant->merchantinfo()->first()) {
                $rewardsobj['provider'] = $merchantinfo['name'];
                $rewardsobj['reward_menu_title'] = 'My '.$merchantinfo['pts_alias'];
                $rewardsobj['pts_alias'] = $merchantinfo['pts_alias'];
                $rewardsobj['showDonated'] = 1;
                $merchantid = $merchantinfo['id'];
               
                $convtable = array();
                $howitworks = 'The miles you earn from your steps are updated at the end of each day.';
                $helptableheader = 'Etihad Miles Conversion';
                $helpinfo = array('tableinfo'=>$convtable,'table_header' => $helptableheader,'hiw_body'=>$howitworks);
                $rewardsobj['rewardshelpinfo'] = $helpinfo;
            }
        }
        */
        return $company_merchant;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function challengestream(Request $request)
    {

    }
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getleaderboard(Request $request)
    {
        $error = $this->validations($request,"company individual_leaderboard");
        if($error['error']) 
        {
            return tupe_prepareResult(false, [], $error['errors'],"missing parameters");
        } 

        $ret = array();
        $leaderdata = array();
        $name = '';
        $steps = '';
        $rank = '';
        $top20 = array();
        $message = '';
        $startdate = '';
        $enddate = '';
        $challenge_title = '';
        $label = 'STEPS';
        $isprocessed =  0;
        $threshold = 7000;
        $_companycode = '';
        $userid = Auth::user()->id;
        

        if(!$challengeinfo = Corpchallenge::find($request['challenge_id']))
        {
            return tupe_prepareResult(false, [], "Challenge id error","Challenge id does not exist.");
        }

        if(! $companyinfo = Company::find($request['company_id']))
        {
            return tupe_prepareResult(false, [], "Company id error","Company id does not exist.");
        }

        // $challengeinfo = $challengeinfo->toArray();

        $challenge_unit = $challengeinfo->challenge_unit;
        $startdate = $challengeinfo->startdate;
        $enddate = $challengeinfo->enddate;
        $challenge_title = $challengeinfo->challenge_header;
        $challenge_cat = $challengeinfo->challenge_cat;
        $isprocessed = $challengeinfo->isprocessed;
    //  $challengeid = $challengerow['id'];
        // $companyinfo = $companyinfo->toArray();

        if(!empty($challengeinfo->threshold)) {
            $threshold = $challengeinfo->threshold;
        }
        
        try {
            $companydetails = $companyinfo->code()->first();
        } catch (Exception $e) {
            return tupe_prepareResult(false, [], "Company code error","Company code does not exist.");
        }
        
        $_companycode = $companydetails->company_code;
        
        if(!$userinfo = User::find(Auth::user()->id))
        {
            return tupe_prepareResult(false, [], "User ID error","User ID does not exist.");
        }

        if(! $usergroup = $userinfo->group()->first())
        {
            return tupe_prepareResult(false, [], "User Group error","User Group does not exist.");
        }
        
        $usergroupid = $usergroup->id;
        $userclusterid = $usergroup->cluster_id;
        
        if(!$userprofile = $userinfo->profile()->first())
        {
            return tupe_prepareResult(false, [], "User Profile error","User Profile does not exist.");
        }

        if(!empty($userprofile->timezone_name))
        {
            date_default_timezone_set($userprofile->timezone_name);
        }
        // if($request->has('date')) {
        $today = Carbon::parse($request['date']);
        // } else {
        //     $today = Carbon::today();
        // }
        $todaystring = Carbon::parse($request['date'])->toDateTimeString();
        
        if($challenge_cat == Config::get('constants.challenges.CHALLENGE_DAILY')) {
        //  $today = mktime(0, 0, 0, 1,28, 2015);
            // $midnight = mktime(23, 59, 0, date("m", $today), date("d", $today), date("Y", $today));
            $todayeod = Carbon::parse($request['date'])->endOfDay();
            $startdate = $todaystring;
            // $startdate = date('Y-m-d H:i:s',$today);
            // $enddate = date('Y-m-d H:i:s',$midnight);
            $enddate = $todayeod->toDateTimeString();
        }
        $userprofile = UserProfile::where('user_id',Auth::user()->id)->first();
        switch ($challenge_unit) {
            case Config::get('constants.challengetype.CHALLENGE_1'):
            case Config::get('constants.challengetype.CHALLENGE_7'):
                $challengeresult = Corpchallenge::find($challengeinfo->id)->challenge1_results()->get();
                foreach ($challengeresult as $resultrow) {
                    # code...
                    $leaderdata[] = array('name'=> $resultrow->fullname,'steps' => number_format($resultrow->totalsteps),'photourl' => $resultrow->avatar ? asset('storage').'/'.$resultrow->avatar : asset('storage').'/'.'avatars/avatar.png','uid'=>$resultrow->user_id);
                }
        //      $leaderboarddata = challenge_challenge1_query($corpid,$startdate,$enddate,FALSE);
                /*
                while($leaderrow = mysql_fetch_assoc($leaderboarddata)) {
                    $urlpath = '';
                    if($isprocessed) {
                        $uid = $leaderrow['uid'];
                        $urlpath = $leaderrow['photourl'];
                        $username = $leaderrow['name'];
                    } else {
                        $uid = $leaderrow['userid'];
                        $cp = content_profile_load('profile', $uid);
                        if($cp) {
                            if($cp->field_avatar[0]['filepath']) {
                                $urlpath = $base_url . '/' . $cp->field_avatar[0]['filepath'];
                            }   
                        }
                        $username = $leaderrow['username'];
                    }
        
                    $total = '';
                    $participants = '';
                    if(validateMail($username)) {
                        $removedomain = explode('@',$username);
                        if(count($removedomain) > 0) {
                            $username = $removedomain[0];
                        }
                    }
                    // this is to only show the users who breaks 150K steps for CHALLENGE_7 on the last day of the challenge and forward
                    if($challenge_unit == CHALLENGE_7 && time() > strtotime($enddate)) {
                        // error_log("CHALLENGE_7 today is greater than enddate\n",3,"/tmp/getchallenge.log");
                        if($leaderrow['steps'] >= 150000) {
                            $leaderdata[] = array('name'=> $username,'steps' => number_format($leaderrow['steps']),'photourl' => $urlpath,'uid'=>$uid,'total'=>$total,'participants'=>$participants);
                        } else {
                            break;  //stop the while loop since the last read data steps is below 150000; then the rest of the data is below 150000
                        }
                    } else {
                        $leaderdata[] = array('name'=> $username,'steps' => number_format($leaderrow['steps']),'photourl' => $urlpath,'uid'=>$uid,'total'=>$total,'participants'=>$participants);
                    }
                    
                }
                */
                $userrank = 0;
                $usersteps = 0;
                if(count($leaderdata) > 0) {
                //      error_log("leaderdata is not empty\n",3,"/tmp/getchallenge.log");
                    $userrank = tupe_search_id($leaderdata,$userid,'uid');
                }
                //  $userrank = $key = array_search($userid, array_column($leaderdata, 'uid'));
                if($userrank > 0) {
                        // put -1 since we add 1 in the search_id return;
                    $name = $leaderdata[$userrank-1]['name'];
                    $usersteps = $leaderdata[$userrank-1]['steps'];
                }
                $rank = $userrank;
                $steps = $usersteps;
                if($rank > 0) {
                    $message = sprintf('Your rank is %d in this challenge with %d steps',$rank,$steps);
                    // $message = 'Your rank is'. $rank . ' in this challenge with '.$steps.' steps';
                } else {
                    $message = 'You are not part of this challenge.';
                }
                
            break;
            
            case Config::get('constants.challengetype.CHALLENGE_2'):
                $challengeresult = Corpchallenge::find($challengeinfo->id)->challenge2_results()->get();
                foreach ($challengeresult as $resultrow) {
                    # code...
                    $leaderdata[] = array('name'=> $resultrow->city,'steps' => number_format($resultrow->avgsteps),'total'=>$resultrow->totalsteps,'participants'=>$resultrow->participants,'photourl' => '','uid'=>'');
                }
                // while($leaderrow = mysql_fetch_assoc($leaderboarddata)) {
                //     if($isprocessed) {
                //         $average = number_format($leaderrow['steps']);
                //         $city = $leaderrow['name'];
                //     } else {
                //         $average = number_format($leaderrow['avgsteps']);
                //         $city = $leaderrow['city'];
                //     }
                //     $urlpath = '';
                //     $uid = '';
                    
                //     $leaderdata[] = array('name'=> $city,'steps' => $average,'photourl' => $urlpath,'uid'=>$uid,'total'=>$leaderrow['totalsteps'],'participants'=>$leaderrow['participants']);
                
                // }
                
        //      error_log("leaderdata2: ".print_r($leaderdata,true)."\n",3,"/tmp/getchallenge.log");
                
                
                $userrank = 0;
                $usersteps = 0;
                if(count($leaderdata) > 0) {
                //      error_log("leaderdata is not empty\n",3,"/tmp/getchallenge.log");
                    $userrank = tupe_search_id($leaderdata,$userprofile->city,'name');
                }
        
        
                //  $userrank = $key = array_search($userid, array_column($leaderdata, 'uid'));
                if($userrank > 0) {
                        // put -1 since we add 1 in the search_id return;
                    $avgsteps = $leaderdata[$userrank-1]['steps'];
                    // $avgsteps = number_format($avgsteps);
                    $name = $userprofile->city;
                    $steps = $avgsteps;
                    $rank = $userrank;
                }
                $label = 'AVERAGE';
                if($rank > 0) {
                    $message = sprintf('Your city rank is %d in this challenge with %d average steps',$rank,$steps);
                    // $message = 'Your city rank is '. $rank . ' in this challenge with '.$steps.' average steps';
                } else {
                    $message = 'You are not part of this challenge.';
                }
            break;
            case Config::get('constants.challengetype.CHALLENGE_3'):
                $challengeresult = Corpchallenge::find($challengeinfo->id)->challenge3_results()->get();
                foreach ($challengeresult as $resultrow) {
                    # code...
                    $leaderdata[] = array('name'=> $resultrow->gender,'steps' => number_format($resultrow->avgsteps),'total'=>$resultrow->totalsteps,'participants'=>$resultrow->participants,'photourl' => '','uid'=>'');
                }
                
                $userrank = 0;
                $usersteps = 0;
                $user_gender = $userprofile->gender ? 'MALE' : 'FEMALE';
                if(count($leaderdata) > 0) {
            //      error_log("leaderdata is not empty\n",3,"/tmp/getchallenge.log");
                    $userrank = tupe_search_id($leaderdata,$user_gender,'name');
                }


            //  $userrank = $key = array_search($userid, array_column($leaderdata, 'uid'));
                if($userrank > 0) {
                    // put -1 since we add 1 in the search_id return;
                    $avgsteps = $leaderdata[$userrank-1]['steps'];
                    // $avgsteps = number_format($avgsteps);
                    $name = $user_gender;
                    $steps = $avgsteps;
                    $rank = $userrank;
                }
                $label = 'AVERAGE';
                // test data
                
                // end of test data
                if($rank > 0) {
                    $message = sprintf('Your gender rank is %d in this challenge with %d average steps',$rank,$steps);
                    // $message = 'Your gender rank is '. $rank . ' in this challenge with '.$steps.' average steps';
                } else {
                    $message = 'You are not part of this challenge.';
                }
            break;
            
            case Config::get('constants.challengetype.CHALLENGE_4'):
                $challengeresult = Corpchallenge::find($challengeinfo->id)->challenge4_results()->get();
                foreach ($challengeresult as $resultrow) {
                    # code...
                    $leaderdata[] = array('name'=> $resultrow->country,'steps' => number_format($resultrow->avgsteps),'total'=>$resultrow->totalsteps,'participants'=>$resultrow->participants,'photourl' => '','uid'=>'');
                }
        //      error_log("leaderdata2: ".print_r($leaderdata,true)."\n",3,"/tmp/getchallenge.log");
        
                $usercountry = $userprofile->country;
                $userrank = 0;
                $usersteps = 0;
                if(count($leaderdata) > 0) {
                //      error_log("leaderdata is not empty\n",3,"/tmp/getchallenge.log");
                    $userrank = tupe_search_id($leaderdata,$usercountry,'name');
                }
        
        
                //  $userrank = $key = array_search($userid, array_column($leaderdata, 'uid'));
                if($userrank > 0) {
                        // put -1 since we add 1 in the search_id return;
                    $avgsteps = $leaderdata[$userrank-1]['steps'];
                    // $avgsteps = number_format($avgsteps);
                    $name = $usercountry;
                    $steps = $avgsteps;
                    $rank = $userrank;
                }
                $label = 'AVERAGE';
                if($rank > 0) {
                    $message = sprintf('Your country rank is %d in this challenge with %d average steps',$rank,$steps);
                    // $message = 'Your country rank is '. $rank . ' in this challenge with '.$steps.' average steps';
                } else {
                    $message = 'You are not part of this challenge.';
                }
            break;
            /*
            case Config::get('constants.challengetype.CHALLENGE_5'):
                if($isprocessed) {
                    $leaderboarddata = $dbcon->doQuery("SELECT * from hmm.challenge_winners where challengeid=$challengeid");
        
                } else {
                    $leaderboarddata = challenge_challenge5_query($corpid,$startdate,$enddate,FALSE);
                }
                $councilorder[] = array();
                while($leaderrow = mysql_fetch_assoc($leaderboarddata)) {
            //      $leaderdata[] = array('city'=> $leaderrow['city'],'avgsteps' => $leaderrow['avgsteps'],'totalsteps'=>$leaderrow['totalsteps'],'numparticipants'=> $leaderrow['participants']);
                    
                    $urlpath = '';
                    $uid = '';
                    if($isprocessed) {
                        $councilname = $leaderrow['name'];
                        $rnd = number_format($leaderrow['steps']);
                    } else {
                        $councilname = hmm_challenge_service_getCouncilname($leaderrow['council_type']);
                        if(strlen($councilname) > 43) {
                            // error_log("council name is more than 43\n",3,"/tmp/councilstr.log");
                            $councilname = substr_replace($councilname,"...",40);
                        }
                        $rnd = number_format($leaderrow['avgsteps']);
                    }
                //  $leaderdata2[] = array('name'=> $leaderrow['council_type'],'steps' => $leaderrow['avgsteps'],'photourl' => $urlpath,'uid'=>$uid,'total'=>$leaderrow['totalsteps'],'participants'=>$leaderrow['participants']);
                    if(!empty($councilname)) {
                        $leaderdata[] = array('name'=> $councilname,'steps' => $rnd,'photourl' => $urlpath,'uid'=>$uid,'total'=>$leaderrow['totalsteps'],'participants'=>$leaderrow['participants'],'council_id'=>$leaderrow['council_type']);
                    }
            //      $councilorder[]['name'] = $leaderrow['council_type'];
                }
        
                $usercouncil = '';
                $userdata = $dbcon->doQuery("SELECT * from drupal.council_info where uid=$userid");
                if($row = mysql_fetch_assoc($userdata)) {
                    $usercouncil = $row['council_type'];
                }
                $userrank = 0;
                $usersteps = 0;
                if(count($leaderdata) > 0) {
                //      error_log("leaderdata is not empty\n",3,"/tmp/getchallenge.log");
                    $userrank = search_id($leaderdata,$usercouncil,'council_id');
                }
        //      error_log("leaderdata: ".print_r($leaderdata,true)."\n",3,"/tmp/getchallenge.log");
        //      error_log("usercouncil: $usercouncil\n",3,"/tmp/getchallenge.log");
        
                //  $userrank = $key = array_search($userid, array_column($leaderdata, 'uid'));
                if($userrank > 0) {
                        // put -1 since we add 1 in the search_id return;
                    $avgsteps = $leaderdata[$userrank-1]['steps'];
                    $avgsteps = number_format($avgsteps);
                    $name = $leaderdata[$userrank-1]['name'];
                    $steps = $avgsteps;
                    $rank = $userrank;
                }
                $label = 'AVERAGE';
                if($rank > 0) {
                    $message = 'Your council rank is '. $rank . ' in this challenge with '.$steps.' average steps';
                } else {
                    $message = 'You are not part of this challenge.';
                }
            break;
            */
            case Config::get('constants.challengetype.CHALLENGE_6'):
                $challengeresult = Corpchallenge::find($challengeinfo->id)->challenge6_results()->get();
                foreach ($challengeresult as $resultrow) {
                    # code...
                    $agegroupname = tupe_getAgeGroupName($resultrow->agegroup);
                    $leaderdata[] = array('name'=> $agegroupname,'steps' => number_format($resultrow->avgsteps),'total'=>$resultrow->totalsteps,'participants'=>$resultrow->participants,'photourl' => '','uid'=>'','agegroup'=>$resultrow->agegroup);
                }
                
        //      error_log("leaderdata2: ".print_r($leaderdata,true)."\n",3,"/tmp/getchallenge.log");
                
                $useragegroup = $userprofile->agegroup;
                $userrank = 0;
                $usersteps = 0;
                if(count($leaderdata) > 0) {
                //      error_log("leaderdata is not empty\n",3,"/tmp/getchallenge.log");
                    $userrank = tupe_search_id($leaderdata,$useragegroup,'agegroup');
                }
        
        
                //  $userrank = $key = array_search($userid, array_column($leaderdata, 'uid'));
                if($userrank > 0) {
                        // put -1 since we add 1 in the search_id return;
                    $avgsteps = $leaderdata[$userrank-1]['steps'];
                    // $avgsteps = number_format($avgsteps);
                    $name = tupe_getAgeGroupName($useragegroup);
                    $steps = $avgsteps;
                    $rank = $userrank;
                }
                $label = 'AVERAGE';
                if($rank > 0) {
                    $message = sprintf('Your age group rank is %d in this challenge with %d average steps',$rank,$steps);
                    // $message = 'Your age group rank is '. $rank . ' in this challenge with '.$steps.' average steps';
                } else {
                    $message = 'You are not part of this challenge.';
                }
            break;
            case Config::get('constants.challengetype.CHALLENGE_8'):
                $challengeresult = Corpchallenge::find($challengeinfo->id)->challenge8_results()->get();
                foreach ($challengeresult as $resultrow) {
                    # code...
                    $leaderdata[] = array('name'=> $resultrow->fullname,'steps' => number_format($resultrow->avgsteps),'total'=>$resultrow->totalsteps,'participants'=>0,'numdaysync'=>$resultrow->numdayssync,'photourl' => $resultrow->avatar ? asset('storage').'/'.$resultrow->avatar : asset('storage').'/'.'avatars/avatar.png','uid'=>$resultrow->user_id);
                }
    
                $userrank = 0;
                $usersteps = 0;
                $label = 'AVERAGE';
                if(count($leaderdata) > 0) {
                //      error_log("leaderdata is not empty\n",3,"/tmp/getchallenge.log");
                    $userrank = tupe_search_id($leaderdata,$userprofile->user_id,'uid');
                }
                //  $userrank = $key = array_search($userid, array_column($leaderdata, 'uid'));
                if($userrank > 0) {
                        // put -1 since we add 1 in the search_id return;
                    $name = $leaderdata[$userrank-1]['name'];
                    $usersteps = $leaderdata[$userrank-1]['steps'];
                }
                $rank = $userrank;
                $steps = $usersteps;
                if($rank > 0) {
                    $message = sprintf('Your rank is %d in this challenge with %d average steps',$rank,$steps);
                } else {
                    $message = 'You are not part of this challenge.';
                }
                
            break;
            /*
            case Config::get('constants.challengetype.CHALLENGE_10'):   //2010
                if($isprocessed) {
    //              error_log("isprocessed is 1\n",3,"/tmp/getchallenge.log");
                    $leaderboarddata = $dbcon->doQuery("SELECT * from hmm.challenge_winners where challengeid=$challengeid");
                    
                } else {
    //              error_log("isprocessed is 0\n",3,"/tmp/getchallenge.log");
        //          error_log("getleaderboard getting from d_pm\n",3,"/tmp/winners.log");
                    // challenge_10Kstepsady_query($corpid, $threshold = 10000, $startdate, $enddate, $isTop = FALSE)
                   
                    $leaderboarddata = challenge_topavgsteps_numdays($corpid,$threshold,$startdate,$enddate,FALSE);
                }
        //      $leaderboarddata = challenge_challenge1_query($corpid,$startdate,$enddate,FALSE);
                while($leaderrow = mysql_fetch_assoc($leaderboarddata)) {
                    $urlpath = '';
                    if($isprocessed) {
                        $uid = $leaderrow['uid'];
                        $urlpath = $leaderrow['photourl'];
                        $username = $leaderrow['name'];
                    } else {
                        $uid = $leaderrow['userid'];
                        $cp = content_profile_load('profile', $uid);
                        if($cp) {
                            if($cp->field_avatar[0]['filepath']) {
                                $urlpath = $base_url . '/' . $cp->field_avatar[0]['filepath'];
                            }   
                        }
                        $username = $leaderrow['username'];
                    }
        
                    $total = '';
                    $participants = '';
                    if(validateMail($username)) {
                        $removedomain = explode('@',$username);
                        if(count($removedomain) > 0) {
                            $username = $removedomain[0];
                        }
                    }
                    // this leaderboard will only show number of days that a user surpassed 10,000 steps in a day
                    $leaderdata[] = array('name'=> $username,'steps' => number_format($leaderrow['numdays']),'photourl' => $urlpath,'uid'=>$uid,'total'=>$total,'participants'=>$participants);
                }
                $userrank = 0;
                $usersteps = 0;
                $label = 'DAYS';
                if(count($leaderdata) > 0) {
                //      error_log("leaderdata is not empty\n",3,"/tmp/getchallenge.log");
                    $userrank = search_id($leaderdata,$userid,'uid');
                }
                //  $userrank = $key = array_search($userid, array_column($leaderdata, 'uid'));
                if($userrank > 0) {
                        // put -1 since we add 1 in the search_id return;
                    $name = $leaderdata[$userrank-1]['name'];
                    $usersteps = $leaderdata[$userrank-1]['steps'];
                }
                $rank = $userrank;
                $steps = $usersteps;
                if($rank > 0) {
                    $message = 'Your rank is'. $rank . ' in this challenge with '.$steps.' number of days that reached '.number_format($threshold).' steps.';
                    
                } else {
                    $message = 'You are not part of this challenge.';
                }
                
            break;
            case Config::get('constants.challengetype.CHALLENGE_12'):   //2012
                $leaderboarddata = challenge_company_steps_countdown($corpid,$startdate,$enddate);
        //      $leaderboarddata = challenge_challenge1_query($corpid,$startdate,$enddate,FALSE);
                while($leaderrow = mysql_fetch_assoc($leaderboarddata)) {
                    $urlpath = '';
        
                    $total = '';
                    $uid = '';
                    $participants = '';
                    $month = date('F', strtotime($startdate));
                    $name = $month. ' Total Steps';
                    // this leaderboard will only show number of days that a user surpassed 10,000 steps in a day
                    $leaderdata[] = array('name'=> $name,'steps' => number_format($leaderrow['totalsteps']),'photourl' => $urlpath,'uid'=>$uid,'total'=>$total,'participants'=>$participants);
                }
                $userrank = 0;
                $usersteps = 0;
                $message = '';
                $label = 'TOTAL STEPS';
                
            break;
            case Config::get('constants.challengetype.CHALLENGE_GROUP_IND_TOPSTEPS'):   //2016
            //  $leaderboarddata = challenge_challenge6_query($corpid,$startdate,$enddate,FALSE);
                $leaderboarddata = challenge_groupsector_topsteps($corpid,FALSE,$usergroupid,$startdate,$enddate,FALSE);
                while($leaderrow = mysql_fetch_assoc($leaderboarddata)) {
                    $urlpath = '';
                    $uid = $leaderrow['userid'];
                    $cp = content_profile_load('profile', $uid);
                    if($cp) {
                        if($cp->field_avatar[0]['filepath']) {
                        $urlpath = $base_url . '/' . $cp->field_avatar[0]['filepath'];
                    }   
                    }
                    $total = '';
                    $participants = '';
                    $username = $leaderrow['username'];
                    if(validateMail($username)) {
                        $removedomain = explode('@',$username);
                        if(count($removedomain) > 0) {
                            $username = $removedomain[0];
                        }
                    }
                    $leaderdata[] = array('name'=> $username,'steps' => number_format($leaderrow['steps']),'photourl' => $urlpath,'uid'=>$uid,'total'=>$total,'participants'=>$participants);
                }
                $userrank = 0;
                $usersteps = 0;
                if(count($leaderdata) > 0) {
                //      error_log("leaderdata is not empty\n",3,"/tmp/getchallenge.log");
                    $userrank = search_id($leaderdata,$userid,'uid');
                }
                //  $userrank = $key = array_search($userid, array_column($leaderdata, 'uid'));
                if($userrank > 0) {
                        // put -1 since we add 1 in the search_id return;
                    $name = $leaderdata[$userrank-1]['name'];
                    $usersteps = $leaderdata[$userrank-1]['steps'];
                }
                $label = 'STEPS';
                $rank = $userrank;
                $steps = $usersteps;
                if($rank > 0) {
                    $message = 'Your rank is'. $rank . ' in this challenge with '.$steps.' steps';
                } else {
                    $message = 'You are not part of this challenge.';
                }
            break;
            case Config::get('constants.challengetype.CHALLENGE_CLUSTER_IND_TOPSTEPS'):   // 2017
                $leaderboarddata = challenge_groupsector_topsteps($corpid,TRUE,$userclusterid,$startdate,$enddate,FALSE);
                while($leaderrow = mysql_fetch_assoc($leaderboarddata)) {
                    $urlpath = '';
                    $uid = $leaderrow['userid'];
                    $cp = content_profile_load('profile', $uid);
                    if($cp) {
                        if($cp->field_avatar[0]['filepath']) {
                        $urlpath = $base_url . '/' . $cp->field_avatar[0]['filepath'];
                    }   
                    }
                    $total = '';
                    $participants = '';
                    $username = $leaderrow['username'];
                    if(validateMail($username)) {
                        $removedomain = explode('@',$username);
                        if(count($removedomain) > 0) {
                            $username = $removedomain[0];
                        }
                    }
                    $leaderdata[] = array('name'=> $username,'steps' => number_format($leaderrow['steps']),'photourl' => $urlpath,'uid'=>$uid,'total'=>$total,'participants'=>$participants);
                }
                $userrank = 0;
                $usersteps = 0;
                if(count($leaderdata) > 0) {
                //      error_log("leaderdata is not empty\n",3,"/tmp/getchallenge.log");
                    $userrank = search_id($leaderdata,$userid,'uid');
                }
                //  $userrank = $key = array_search($userid, array_column($leaderdata, 'uid'));
                if($userrank > 0) {
                        // put -1 since we add 1 in the search_id return;
                    $name = $leaderdata[$userrank-1]['name'];
                    $usersteps = $leaderdata[$userrank-1]['steps'];
                }
                $rank = $userrank;
                $label = 'STEPS';
                $steps = $usersteps;
                if($rank > 0) {
                    $message = 'Your rank is'. $rank . ' in this challenge with '.$steps.' steps';
                } else {
                    $message = 'You are not part of this challenge.';
                }
            break;
            case Config::get('constants.challengetype.CHALLENGE_GROUP_IND_TOPMINUTES'):   /// active minutes is only for mymo users
                // challenge_groupsector_activeminutes($orgid,$clustermode = FALSE,$groupid,$startdate,$enddate,$isTop = FALSE)
                $leaderboarddata = challenge_groupsector_activeminutes($corpid,FALSE,$usergroupid,$startdate,$enddate,FALSE);
                while($leaderrow = mysql_fetch_assoc($leaderboarddata)) {
                    $urlpath = '';
                    $uid = $leaderrow['userid'];
                    $cp = content_profile_load('profile', $uid);
                    if($cp) {
                        if($cp->field_avatar[0]['filepath']) {
                        $urlpath = $base_url . '/' . $cp->field_avatar[0]['filepath'];
                    }   
                    }
                    $total = '';
                    $participants = '';
                    $username = $leaderrow['username'];
                    if(validateMail($username)) {
                        $removedomain = explode('@',$username);
                        if(count($removedomain) > 0) {
                            $username = $removedomain[0];
                        }
                    }
                    $leaderdata[] = array('name'=> $username,'steps' => number_format($leaderrow['steps']),'photourl' => $urlpath,'uid'=>$uid,'total'=>$total,'participants'=>$participants);
                }
                $userrank = 0;
                $usersteps = 0;
                if(count($leaderdata) > 0) {
                //      error_log("leaderdata is not empty\n",3,"/tmp/getchallenge.log");
                    $userrank = search_id($leaderdata,$userid,'uid');
                }
                //  $userrank = $key = array_search($userid, array_column($leaderdata, 'uid'));
                if($userrank > 0) {
                        // put -1 since we add 1 in the search_id return;
                    $name = $leaderdata[$userrank-1]['name'];
                    $usersteps = $leaderdata[$userrank-1]['steps'];
                }
                $rank = $userrank;
                $steps = $usersteps;
                $label = 'MINUTES';
                if($rank > 0) {
                    $message = 'Your rank is'. $rank . ' in this challenge with '.$steps.' steps';
                } else {
                    $message = 'You are not part of this challenge.';
                }
            break;
            case Config::get('constants.challengetype.CHALLENGE_CLUSTER_IND_TOPMINUTES'):   // active minutes is only for mymo users
                $leaderboarddata = challenge_groupsector_activeminutes($corpid,TRUE,$userclusterid,$startdate,$enddate,FALSE);
                while($leaderrow = mysql_fetch_assoc($leaderboarddata)) {
                    $urlpath = '';
                    $uid = $leaderrow['userid'];
                    $cp = content_profile_load('profile', $uid);
                    if($cp) {
                        if($cp->field_avatar[0]['filepath']) {
                        $urlpath = $base_url . '/' . $cp->field_avatar[0]['filepath'];
                    }   
                    }
                    $total = '';
                    $participants = '';
                    $username = $leaderrow['username'];
                    if(validateMail($username)) {
                        $removedomain = explode('@',$username);
                        if(count($removedomain) > 0) {
                            $username = $removedomain[0];
                        }
                    }
                    $leaderdata[] = array('name'=> $username,'steps' => number_format($leaderrow['steps']),'photourl' => $urlpath,'uid'=>$uid,'total'=>$total,'participants'=>$participants);
                }
                $userrank = 0;
                $usersteps = 0;
                if(count($leaderdata) > 0) {
                //      error_log("leaderdata is not empty\n",3,"/tmp/getchallenge.log");
                    $userrank = search_id($leaderdata,$userid,'uid');
                }
                //  $userrank = $key = array_search($userid, array_column($leaderdata, 'uid'));
                if($userrank > 0) {
                        // put -1 since we add 1 in the search_id return;
                    $name = $leaderdata[$userrank-1]['name'];
                    $usersteps = $leaderdata[$userrank-1]['steps'];
                }
                $rank = $userrank;
                $label = 'MINUTES';
                $steps = $usersteps;
                if($rank > 0) {
                    $message = 'Your rank is'. $rank . ' in this challenge with '.$steps.' steps';
                } else {
                    $message = 'You are not part of this challenge.';
                }
            break;
            case Config::get('constants.challengetype.CHALLENGE_CLUSTER_GRP_TOPSTEPS'):   //2020
                $leaderboarddata = challenge_groupsector_cluster_topgroup_topsteps($corpid,$userclusterid,$startdate,$enddate,FALSE);
                $councilorder[] = array();
                while($leaderrow = mysql_fetch_assoc($leaderboarddata)) {
            //      $leaderdata[] = array('city'=> $leaderrow['city'],'avgsteps' => $leaderrow['avgsteps'],'totalsteps'=>$leaderrow['totalsteps'],'numparticipants'=> $leaderrow['participants']);
                
                    $urlpath = '';
                    $uid = '';
                
                //  $leaderdata2[] = array('name'=> $leaderrow['council_type'],'steps' => $leaderrow['avgsteps'],'photourl' => $urlpath,'uid'=>$uid,'total'=>$leaderrow['totalsteps'],'participants'=>$leaderrow['participants']);
                    if(!empty($leaderrow['photourl'])) {
                        $urlpath = $base_url .'/'.$leaderrow['photourl'];
                    }
                    if(!empty($leaderrow['name'])) {
                        $rnd = number_format($leaderrow['average']);
                        $leaderdata[] = array('name'=> $leaderrow['name'],'steps' => $rnd,'photourl' => $urlpath,'uid'=>$uid,'total'=>$leaderrow['totalsteps'],'participants'=>$leaderrow['participants'],'groupid' =>$leaderrow['groupid']);
                    }
            //      $councilorder[]['name'] = $leaderrow['council_type'];
            
                }
                $userrank = 0;
                $usersteps = 0;
                if(count($leaderdata) > 0) {
                //      error_log("leaderdata is not empty\n",3,"/tmp/getchallenge.log");
                    $userrank = search_id($leaderdata,$userclusterid,'groupid');
                }
        //      error_log("leaderdata: ".print_r($leaderdata,true)."\n",3,"/tmp/getchallenge.log");
        //      error_log("usercouncil: $usercouncil\n",3,"/tmp/getchallenge.log");

                //  $userrank = $key = array_search($userid, array_column($leaderdata, 'uid'));
                if($userrank > 0) {
                        // put -1 since we add 1 in the search_id return;
                    $avgsteps = $leaderdata[$userrank-1]['steps'];
                    $avgsteps = number_format($avgsteps);
                    $name = $leaderdata[$userrank-1]['name'];
                    $steps = $avgsteps;
                    $rank = $userrank;
                }
                $label = 'AVERAGE';
                if($rank > 0) {
                    $message = 'Your group rank is '. $rank . ' in this challenge with '.$steps.' average steps';
                } else {
                    $message = 'You are not part of this challenge.';
                }
            break;
            case Config::get('constants.challengetype.CHALLENGE_COMPANY_GRP_TOPSTEPS'):   //2021
                //challenge_groupsector_company_topgroup_topsteps($orgid,$startdate,$enddate,$isTop = FALSE)
                $leaderboarddata = challenge_groupsector_company_topgroup_topsteps($corpid,$startdate,$enddate,FALSE);
                $councilorder[] = array();
                while($leaderrow = mysql_fetch_assoc($leaderboarddata)) {
                    $urlpath = '';
                    $uid = '';
                    if(!empty($leaderrow['photourl'])) {
                        $urlpath = $base_url .'/'.$leaderrow['photourl'];
                    }
                    if(!empty($leaderrow['name'])) {
                        $rnd = number_format($leaderrow['average']);
                        $leaderdata[] = array('name'=> $leaderrow['name'],'steps' => $rnd,'photourl' => $urlpath,'uid'=>$uid,'total'=>$leaderrow['totalsteps'],'participants'=>$leaderrow['participants'],'groupid' =>$leaderrow['groupid']);
                    }
                }
                $userrank = 0;
                $usersteps = 0;
                if(count($leaderdata) > 0) {
                //      error_log("leaderdata is not empty\n",3,"/tmp/getchallenge.log");
                    $userrank = search_id($leaderdata,$userclusterid,'groupid');
                }
                if($userrank > 0) {
                        // put -1 since we add 1 in the search_id return;
                    $avgsteps = $leaderdata[$userrank-1]['steps'];
                    $avgsteps = number_format($avgsteps);
                    $name = $leaderdata[$userrank-1]['name'];
                    $steps = $avgsteps;
                    $rank = $userrank;
                }
                $label = 'AVERAGE';
                if($rank > 0) {
                    $message = 'Your group rank is '. $rank . ' in this challenge with '.$steps.' average steps';
                } else {
                    $message = 'You are not part of this challenge.';
                }
            break;
            case Config::get('constants.challengetype.CHALLENGE_CLUSTER_GRP_TOPMINUTES'):   // active minutes is only for mymo users
                $leaderboarddata = challenge_groupsector_cluster_topgroup_topminutes($corpid,$userclusterid,$startdate,$enddate,FALSE);
                $councilorder[] = array();
                while($leaderrow = mysql_fetch_assoc($leaderboarddata)) {
                    $urlpath = '';
                    $uid = '';
                    if(!empty($leaderrow['photourl'])) {
                        $urlpath = $base_url .'/'.$leaderrow['photourl'];
                    }
                    if(!empty($leaderrow['name'])) {
                        $rnd = number_format($leaderrow['average']);
                        $leaderdata[] = array('name'=> $leaderrow['name'],'steps' => $rnd,'photourl' => $urlpath,'uid'=>$uid,'total'=>$leaderrow['totalsteps'],'participants'=>$leaderrow['participants'],'groupid' =>$leaderrow['groupid']);
                    }
            //      $councilorder[]['name'] = $leaderrow['council_type'];
        
                }
                $userrank = 0;
                $usersteps = 0;
                if(count($leaderdata) > 0) {
                //      error_log("leaderdata is not empty\n",3,"/tmp/getchallenge.log");
                    $userrank = search_id($leaderdata,$userclusterid,'groupid');
                }
        //      error_log("leaderdata: ".print_r($leaderdata,true)."\n",3,"/tmp/getchallenge.log");
        //      error_log("usercouncil: $usercouncil\n",3,"/tmp/getchallenge.log");

                //  $userrank = $key = array_search($userid, array_column($leaderdata, 'uid'));
                if($userrank > 0) {
                        // put -1 since we add 1 in the search_id return;
                    $avgsteps = $leaderdata[$userrank-1]['steps'];
                    $avgsteps = number_format($avgsteps);
                    $name = $leaderdata[$userrank-1]['name'];
                    $steps = $avgsteps;
                    $rank = $userrank;
                }
                $label = 'AVERAGE';
                if($rank > 0) {
                    $message = 'Your group rank is '. $rank . ' in this challenge with '.$steps.' average steps';
                } else {
                    $message = 'You are not part of this challenge.';
                }
            break;
            case Config::get('constants.challengetype.CHALLENGE_COMPANY_GRP_TOPMINUTES'):   // active minutes is only for mymo users
                $leaderboarddata = challenge_groupsector_company_topgroup_topminutes($corpid,$startdate,$enddate,FALSE);
                $councilorder[] = array();
                while($leaderrow = mysql_fetch_assoc($leaderboarddata)) {
                    $urlpath = '';
                    $uid = '';
                    if(!empty($leaderrow['photourl'])) {
                        $urlpath = $base_url .'/'.$leaderrow['photourl'];
                    }
                    if(!empty($leaderrow['name'])) {
                        $rnd = number_format($leaderrow['average']);
                        $leaderdata[] = array('name'=> $leaderrow['name'],'steps' => $rnd,'photourl' => $urlpath,'uid'=>$uid,'total'=>$leaderrow['totalsteps'],'participants'=>$leaderrow['participants'],'groupid' =>$leaderrow['groupid']);
                    }
            //      $councilorder[]['name'] = $leaderrow['council_type'];

                }
                $userrank = 0;
                $usersteps = 0;
                if(count($leaderdata) > 0) {
                //      error_log("leaderdata is not empty\n",3,"/tmp/getchallenge.log");
                    $userrank = search_id($leaderdata,$userclusterid,'groupid');
                }
        //      error_log("leaderdata: ".print_r($leaderdata,true)."\n",3,"/tmp/getchallenge.log");
        //      error_log("usercouncil: $usercouncil\n",3,"/tmp/getchallenge.log");

                //  $userrank = $key = array_search($userid, array_column($leaderdata, 'uid'));
                if($userrank > 0) {
                        // put -1 since we add 1 in the search_id return;
                    $avgsteps = $leaderdata[$userrank-1]['steps'];
                    $avgsteps = number_format($avgsteps);
                    $name = $leaderdata[$userrank-1]['name'];
                    $steps = $avgsteps;
                    $rank = $userrank;
                }
                $label = 'AVERAGE';
                if($rank > 0) {
                    $message = 'Your group rank is '. $rank . ' in this challenge with '.$steps.' average steps';
                } else {
                    $message = 'You are not part of this challenge.';
                }
            break;
            case Config::get('constants.challengetype.CHALLENGE_COMPANY_CLUSTER_TOPSTEPS'):
                if($isprocessed) {
                    $leaderboarddata = $dbcon->doQuery("SELECT * from hmm.challenge_winners where challengeid=$challengeid");

                } else {
                //  $leaderboarddata = challenge_challenge6_query($corpid,$startdate,$enddate,FALSE);
                    $leaderboarddata = challenge_groupscluster_company_topcluster_topsteps($corpid,$startdate,$enddate,FALSE);
                }
                //challenge_groupsector_company_topgroup_topsteps($orgid,$startdate,$enddate,$isTop = FALSE)
                // $leaderboarddata = challenge_groupscluster_company_topcluster_topsteps($corpid,$startdate,$enddate,FALSE);
                // $councilorder[] = array();
                while($leaderrow = mysql_fetch_assoc($leaderboarddata)) {
                    $urlpath = '';
                    $uid = '';
                    if(!empty($leaderrow['photourl'])) {
                        $urlpath = $base_url .'/'.$leaderrow['photourl'];
                    }
                    if(!empty($leaderrow['name'])) {
                        if($isprocessed) {
                            $rnd = number_format($leaderrow['steps']);
                            $thisclstrid = $leaderrow['uid'];
                        } else {
                            $rnd = number_format($leaderrow['average']);
                            $thisclstrid = $leaderrow['clusterid'];
                        }
                        
                        $leaderdata[] = array('name'=> $leaderrow['name'],'steps' => $rnd,'photourl' => $urlpath,'uid'=>$uid,'total'=>$leaderrow['totalsteps'],'participants'=>$leaderrow['participants'],'groupid' =>$thisclstrid);
                    }
                }
                $userrank = 0;
                $usersteps = 0;
                if(count($leaderdata) > 0) {
                //      error_log("leaderdata is not empty\n",3,"/tmp/getchallenge.log");
                    $userrank = search_id($leaderdata,$userclusterid,'groupid');
                }
                if($userrank > 0) {
                            // put -1 since we add 1 in the search_id return;
                    $avgsteps = $leaderdata[$userrank-1]['steps'];
                    $avgsteps = number_format($avgsteps);
                    $name = $leaderdata[$userrank-1]['name'];
                    $steps = $avgsteps;
                    $rank = $userrank;
                }
                $label = 'AVERAGE';
                if($rank > 0) {
                    $message = 'Your council rank is '. $rank . ' in this challenge with '.$steps.' average steps';
                } else {
                    $message = 'You are not part of this challenge.';
                }
                break;
                case Config::get('constants.challengetype.CHALLENGE_CLUSTER_IND_TOPAVGSTEPS_NUMDAYS'):   //2025
                    $leaderboarddata = challenge_groupsector_topavgsteps_numdays($corpid,TRUE,$userclusterid,$startdate,$enddate,FALSE,$threshold);
                    while($leaderrow = mysql_fetch_assoc($leaderboarddata)) {
                        $urlpath = '';
                        $uid = $leaderrow['userid'];
                        $cp = content_profile_load('profile', $uid);
                        if($cp) {
                            if($cp->field_avatar[0]['filepath']) {
                            $urlpath = $base_url . '/' . $cp->field_avatar[0]['filepath'];
                        }   
                        }
                        $total = '';
                        $participants = '';
                        $username = $leaderrow['username'];
                        if(validateMail($username)) {
                            $removedomain = explode('@',$username);
                            if(count($removedomain) > 0) {
                                $username = $removedomain[0];
                            }
                        }
                        $leaderdata[] = array('name'=> $username,'steps' => number_format($leaderrow['steps']),'photourl' => $urlpath,'uid'=>$uid,'total'=>$total,'participants'=>$participants);
                    }
                    $userrank = 0;
                    $usersteps = 0;
                    if(count($leaderdata) > 0) {
                    //      error_log("leaderdata is not empty\n",3,"/tmp/getchallenge.log");
                        $userrank = search_id($leaderdata,$userid,'uid');
                    }
                    //  $userrank = $key = array_search($userid, array_column($leaderdata, 'uid'));
                    if($userrank > 0) {
                            // put -1 since we add 1 in the search_id return;
                        $name = $leaderdata[$userrank-1]['name'];
                        $usersteps = $leaderdata[$userrank-1]['steps'];
                    }
                    $rank = $userrank;
                    $label = 'DAYS';
                    $steps = $usersteps;
                    if($rank > 0) {
                        $message = 'Your rank is'. $rank . ' in this challenge with '.$steps.' number of days reached '.number_format($threshold).' steps';
                        
                    } else {
                        $message = 'You are not part of this challenge.';
                    }
                break;
                case Config::get('constants.challengetype.CHALLENGE_IND_TOPCALS'):
                    $paramtype = 1;
                    if($isprocessed) {
        //              error_log("isprocessed is 1\n",3,"/tmp/getchallenge.log");
                        $leaderboarddata = $dbcon->doQuery("SELECT * from hmm.challenge_winners where challengeid=$challengeid");
                
                    } else {
        //              error_log("isprocessed is 0\n",3,"/tmp/getchallenge.log");
            //          error_log("getleaderboard getting from d_pm\n",3,"/tmp/winners.log");
                        $leaderboarddata = challenge_extraparamleader_ind_query($corpid,$paramtype,$startdate,$enddate,FALSE);
                    }
            //      $leaderboarddata = challenge_challenge1_query($corpid,$startdate,$enddate,FALSE);
                    while($leaderrow = mysql_fetch_assoc($leaderboarddata)) {
                        $urlpath = '';
                        if($isprocessed) {
                            $uid = $leaderrow['uid'];
                            $urlpath = $leaderrow['photourl'];
                            $username = $leaderrow['name'];
                        } else {
                            $uid = $leaderrow['userid'];
                            $cp = content_profile_load('profile', $uid);
                            if($cp) {
                                if($cp->field_avatar[0]['filepath']) {
                                    $urlpath = $base_url . '/' . $cp->field_avatar[0]['filepath'];
                                }   
                            }
                            $username = $leaderrow['username'];
                        }

                        $total = '';
                        $participants = '';
                        if(validateMail($username)) {
                            $removedomain = explode('@',$username);
                            if(count($removedomain) > 0) {
                                $username = $removedomain[0];
                            }
                        }
                        // number_format((float)$foo, 2, '.', '');
                        $leaderdata[] = array('name'=> $username,'steps' => number_format((float)$leaderrow['steps'],2,'.',','),'photourl' => $urlpath,'uid'=>$uid,'total'=>$total,'participants'=>$participants);
                
                    }
                    $userrank = 0;
                    $usersteps = 0;
                    if(count($leaderdata) > 0) {
                    //      error_log("leaderdata is not empty\n",3,"/tmp/getchallenge.log");
                        $userrank = search_id($leaderdata,$userid,'uid');
                    }
                    //  $userrank = $key = array_search($userid, array_column($leaderdata, 'uid'));
                    if($userrank > 0) {
                            // put -1 since we add 1 in the search_id return;
                        $name = $leaderdata[$userrank-1]['name'];
                        $usersteps = $leaderdata[$userrank-1]['steps'];
                    }
                    $rank = $userrank;
                    $steps = $usersteps;
                    $label = 'CALORIES';
                    if($rank > 0) {
                        $message = 'Your rank is'. $rank . ' in this challenge with '.$steps.' calories burned.';
                    } else {
                        $message = 'You are not part of this challenge.';
                    }
                break;
                case Config::get('constants.challengetype.CHALLENGE_SUB_GROUP_TOPCALS'):
                    $paramtype = 1;     // paramtype for CALORIES
                    $leaderboarddata = challenge_groupsector_company_topgroup_extraparam($corpid,$paramtype,$startdate,$enddate,FALSE);
                    $councilorder[] = array();
                    while($leaderrow = mysql_fetch_assoc($leaderboarddata)) {
                        $urlpath = '';
                        $uid = '';
                        if(!empty($leaderrow['photourl'])) {
                            $urlpath = $base_url .'/'.$leaderrow['photourl'];
                        }
                        if(!empty($leaderrow['name'])) {
                            $rnd = number_format((float)$leaderrow['average'],2,'.',',');
                            // number_format((float)$leaderrow['steps'],2,'.',',')
                            $leaderdata[] = array('name'=> $leaderrow['name'],'steps' => $rnd,'photourl' => $urlpath,'uid'=>$uid,'total'=>$leaderrow['totalsteps'],'participants'=>$leaderrow['participants'],'groupid' =>$leaderrow['groupid']);
                        }
                    }
                    $userrank = 0;
                    $usersteps = 0;
                    if(count($leaderdata) > 0) {
                    //      error_log("leaderdata is not empty\n",3,"/tmp/getchallenge.log");
                        $userrank = search_id($leaderdata,$userclusterid,'groupid');
                    }
                    if($userrank > 0) {
                            // put -1 since we add 1 in the search_id return;
                        $avgsteps = $leaderdata[$userrank-1]['steps'];
                        $avgsteps = number_format($avgsteps);
                        $name = $leaderdata[$userrank-1]['name'];
                        $steps = $avgsteps;
                        $rank = $userrank;
                    }
                    $label = 'AVG. CALORIES';
                    if($rank > 0) {
                        $message = 'Your group rank is '. $rank . ' in this challenge with '.$steps.' average calories burned.';
                    } else {
                        $message = 'You are not part of this challenge.';
                    }
                    break;
                break;
                case Config::get('constants.challengetype.CHALLENGE_IND_TOPDIST'):
                    $paramtype = 2;
                    if($isprocessed) {
        //              error_log("isprocessed is 1\n",3,"/tmp/getchallenge.log");
                        $leaderboarddata = $dbcon->doQuery("SELECT * from hmm.challenge_winners where challengeid=$challengeid");
                
                    } else {
        //              error_log("isprocessed is 0\n",3,"/tmp/getchallenge.log");
            //          error_log("getleaderboard getting from d_pm\n",3,"/tmp/winners.log");
                        $leaderboarddata = challenge_extraparamleader_ind_query($corpid,$paramtype,$startdate,$enddate,FALSE);
                    }
            //      $leaderboarddata = challenge_challenge1_query($corpid,$startdate,$enddate,FALSE);
                    $label = '';
                    $prefs = hmm_challenge_service_fetchcompanyprefs($_companycode);
                    if(!empty($prefs)) {
                        if(isset($prefs['distance'])) {
                            $label = $prefs['distance'];
                        }
                    }
                    while($leaderrow = mysql_fetch_assoc($leaderboarddata)) {
                        $urlpath = '';
                        if($isprocessed) {
                            $uid = $leaderrow['uid'];
                            $urlpath = $leaderrow['photourl'];
                            $username = $leaderrow['name'];
                        } else {
                            $uid = $leaderrow['userid'];
                            $cp = content_profile_load('profile', $uid);
                            if($cp) {
                                if($cp->field_avatar[0]['filepath']) {
                                    $urlpath = $base_url . '/' . $cp->field_avatar[0]['filepath'];
                                }   
                            }
                            $username = $leaderrow['username'];
                        }

                        $total = '';
                        $participants = '';
                        if(validateMail($username)) {
                            $removedomain = explode('@',$username);
                            if(count($removedomain) > 0) {
                                $username = $removedomain[0];
                            }
                        }
                        // number_format((float)$foo, 2, '.', '');
                        // +rodel.12.10.2017.Convert meters to mi or km here based on company prefs
                        $distmeters = $leaderrow['steps'];
                        if($label == 'km') {
                            $new_dist = $distmeters /1000;
                        } else {
                            $new_dist = $distmeters * 0.00062137;
                        }
                        // +rodel.12.10.2017
                        // $leaderdata[] = array('name'=> $username,'steps' => number_format((float)$leaderrow['steps'],2,'.',','),'photourl' => $urlpath,'uid'=>$uid,'total'=>$total,'participants'=>$participants);
                         $leaderdata[] = array('name'=> $username,'steps' => number_format((float)$new_dist,2,'.',','),'photourl' => $urlpath,'uid'=>$uid,'total'=>$total,'participants'=>$participants);
                    }
                    $userrank = 0;
                    $usersteps = 0;
                    if(count($leaderdata) > 0) {
                    //      error_log("leaderdata is not empty\n",3,"/tmp/getchallenge.log");
                        $userrank = search_id($leaderdata,$userid,'uid');
                    }
                    //  $userrank = $key = array_search($userid, array_column($leaderdata, 'uid'));
                    if($userrank > 0) {
                            // put -1 since we add 1 in the search_id return;
                        $name = $leaderdata[$userrank-1]['name'];
                        $usersteps = $leaderdata[$userrank-1]['steps'];
                    }
                    $rank = $userrank;
                    $steps = $usersteps;
                    
                    if($rank > 0) {
                        $message = 'Your rank is'. $rank . ' in this challenge with '.$steps.' '.$label.' distance covered.';
                    } else {
                        $message = 'You are not part of this challenge.';
                    }
                break;
                case Config::get('constants.challengetype.CHALLENGE_SUB_GROUP_TOPDIST'):
                    $paramtype = 2;     // paramtype for CALORIES
                    $leaderboarddata = challenge_groupsector_company_topgroup_extraparam($corpid,$paramtype,$startdate,$enddate,FALSE);
                    $councilorder[] = array();
                    $label = '';
                    $prefs = hmm_challenge_service_fetchcompanyprefs($_companycode);
                    if(!empty($prefs)) {
                        if(isset($prefs['distance'])) {
                            $label = $prefs['distance'];
                        }
                    }
                    while($leaderrow = mysql_fetch_assoc($leaderboarddata)) {
                        $urlpath = '';
                        $uid = '';
                        if(!empty($leaderrow['photourl'])) {
                            $urlpath = $base_url .'/'.$leaderrow['photourl'];
                        }
                        if(!empty($leaderrow['name'])) {
                            
                            $distmeters = $leaderrow['average'];
                            if($label == 'km') {
                                $new_dist = $distmeters /1000;
                            } else {
                                $new_dist = $distmeters * 0.00062137;
                            }
                            $rnd = number_format((float)$new_dist,2,'.',',');
                            // number_format((float)$leaderrow['steps'],2,'.',',')
                            $leaderdata[] = array('name'=> $leaderrow['name'],'steps' => $rnd,'photourl' => $urlpath,'uid'=>$uid,'total'=>$leaderrow['totalsteps'],'participants'=>$leaderrow['participants'],'groupid' =>$leaderrow['groupid']);
                        }
                    }
                    $userrank = 0;
                    $usersteps = 0;
                    if(count($leaderdata) > 0) {
                    //      error_log("leaderdata is not empty\n",3,"/tmp/getchallenge.log");
                        $userrank = search_id($leaderdata,$userclusterid,'groupid');
                    }
                    if($userrank > 0) {
                            // put -1 since we add 1 in the search_id return;
                        $avgsteps = $leaderdata[$userrank-1]['steps'];
                        $avgsteps = number_format($avgsteps);
                        $name = $leaderdata[$userrank-1]['name'];
                        $steps = $avgsteps;
                        $rank = $userrank;
                    }
                    // $label = 'AVG. CALORIES';
                    if($rank > 0) {
                        $message = 'Your group rank is '. $rank . ' in this challenge with '.$steps. ' '.$label.' as average distance covered.';
                    } else {
                        $message = 'You are not part of this challenge.';
                    }
                    break;
                break;
                case Config::get('constants.challengetype.CHALLENGE_IND_TOPFLOORS'):
                    $paramtype = 3;
                    if($isprocessed) {
        //              error_log("isprocessed is 1\n",3,"/tmp/getchallenge.log");
                        $leaderboarddata = $dbcon->doQuery("SELECT * from hmm.challenge_winners where challengeid=$challengeid");
                
                    } else {
        //              error_log("isprocessed is 0\n",3,"/tmp/getchallenge.log");
            //          error_log("getleaderboard getting from d_pm\n",3,"/tmp/winners.log");
                        $leaderboarddata = challenge_extraparamleader_ind_query($corpid,$paramtype,$startdate,$enddate,FALSE);
                    }
            //      $leaderboarddata = challenge_challenge1_query($corpid,$startdate,$enddate,FALSE);
                    $label = 'FLOORS';
                    
                    while($leaderrow = mysql_fetch_assoc($leaderboarddata)) {
                        $urlpath = '';
                        if($isprocessed) {
                            $uid = $leaderrow['uid'];
                            $urlpath = $leaderrow['photourl'];
                            $username = $leaderrow['name'];
                        } else {
                            $uid = $leaderrow['userid'];
                            $cp = content_profile_load('profile', $uid);
                            if($cp) {
                                if($cp->field_avatar[0]['filepath']) {
                                    $urlpath = $base_url . '/' . $cp->field_avatar[0]['filepath'];
                                }   
                            }
                            $username = $leaderrow['username'];
                        }

                        $total = '';
                        $participants = '';
                        if(validateMail($username)) {
                            $removedomain = explode('@',$username);
                            if(count($removedomain) > 0) {
                                $username = $removedomain[0];
                            }
                        }
                         $leaderdata[] = array('name'=> $username,'steps' => $leaderrow['steps'],'photourl' => $urlpath,'uid'=>$uid,'total'=>$total,'participants'=>$participants);
                    }
                    $userrank = 0;
                    $usersteps = 0;
                    if(count($leaderdata) > 0) {
                    //      error_log("leaderdata is not empty\n",3,"/tmp/getchallenge.log");
                        $userrank = search_id($leaderdata,$userid,'uid');
                    }
                    //  $userrank = $key = array_search($userid, array_column($leaderdata, 'uid'));
                    if($userrank > 0) {
                            // put -1 since we add 1 in the search_id return;
                        $name = $leaderdata[$userrank-1]['name'];
                        $usersteps = $leaderdata[$userrank-1]['steps'];
                    }
                    $rank = $userrank;
                    $steps = $usersteps;
                    
                    if($rank > 0) {
                        $message = 'Your rank is'. $rank . ' in this challenge with '.$steps.' '.$label.' reached.';
                    } else {
                        $message = 'You are not part of this challenge.';
                    }
                break;
                case Config::get('constants.challengetype.CHALLENGE_MAIN_STEPS'):
    //          error_log("getleaderboard in main steps\n",3,"/tmp/winners.log");
                if($isprocessed) {
    //              error_log("getleaderboard getting from winners\n",3,"/tmp/winners.log");
                    $leaderboarddata = $dbcon->doQuery("SELECT * from hmm.challenge_winners where challengeid=$challengeid");
                    
                } else {
        //          error_log("getleaderboard getting from d_pm\n",3,"/tmp/winners.log");
                    $leaderboarddata = challenge_challengemainsteps_query($corpid,$startdate,$enddate,FALSE);
                }
                while($leaderrow = mysql_fetch_assoc($leaderboarddata)) {
                    $urlpath = '';
                    $uid = $leaderrow['uid'];
                    if(!empty($leaderrow['photourl'])) {
                        $urlpath = $leaderrow['photourl'];
                    } else {
                        $cp = content_profile_load('profile', $uid);
                        if($cp) {
                            if($cp->field_avatar[0]['filepath']) {
                                $urlpath = $base_url . '/' . $cp->field_avatar[0]['filepath'];
                            }   
                        }
                    }
                    $total = '';
                    $participants = '';
                    $username = $leaderrow['name'];
                    if(validateMail($username)) {
                        $removedomain = explode('@',$username);
                        if(count($removedomain) > 0) {
                            $username = $removedomain[0];
                        }
                    }
                    $leaderdata[] = array('name'=> $username,'steps' => number_format($leaderrow['steps']),'photourl' => $urlpath,'uid'=>$uid,'total'=>$total,'participants'=>$participants);
                }
                $userrank = 0;
                $usersteps = 0;
                
                if(count($leaderdata) > 0) {
            //      error_log("leaderdata is not empty\n",3,"/tmp/getchallenge.log");
                    $userrank = search_id($leaderdata,$userid,'uid');
                }
            //  $userrank = $key = array_search($userid, array_column($leaderdata, 'uid'));
                if($userrank > 0) {
                    // put -1 since we add 1 in the search_id return;
                    $name = $leaderdata[$userrank-1]['name'];
                    $usersteps = $leaderdata[$userrank-1]['steps'];
                }
                $rank = $userrank;
                $steps = $usersteps;
                if($rank > 0) {
                    $message = 'Your rank is '. $rank . ' in this challenge with '.$steps.' steps';
                } else {
                    $message = 'You are not part of this challenge.';
                }
            break;
            */
        }
        /*
        $ctr = 0;
        foreach($leaderdata as $values) {
            $top20[] = $values;
            $ctr++;
            if($ctr > 9) {
                break;
            }
        }
        */
    //  $ret['name'] = $name;
        $ret['name'] = $challenge_title;
        $ret['steps'] = $steps;
        $ret['rank'] = $rank;
        $ret['challenge_start'] = $startdate;
        $ret['challenge_end'] = $enddate;
        $ret['challenge_unit'] = $challenge_unit;
        $ret['challenge_cat'] = $challenge_cat;
        $ret['challenge_label'] = $label;
        $ret['message'] = $message;
        $ret['content'] = $leaderdata;
        //$leaderboarddata = db_query("$qstr",$corpid,$startdate,$enddate);
        return $ret;
    }

    

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


/**API CONTINUATION NIKKO */


public function companyevents(Request $request)
{
//     global $base_url;
//     module_load_include('php', 'hmm_activities', 'dbconnector');
//     $dbcon = new DbConnector();
	
//     if(!$dbcon) {
//         return services_error('1', 403);
//     }
// 	$eventobj = array();
// 	$corpEventQuery = $dbcon->doQuery("SELECT 
// 	t1.id 'eventid',
//     startdate 'event_startdate',
//     enddate 'event_enddate',
//     t2.event_title 'event_name',
//     t2.event_en_body 'en_text',
//     t2.event_ar_body 'ar_text',
//     t2.arExist 'arAvailable',
//     t2.invite_url 'invitation_url',
//     t2.cover_url 'coverimage_url',
//     t2.location 'event_location'
// from
//     hmm.event t1,
//     hmm.event_body t2
// where
//     t1.id = t2.eventid and t1.orgid = $corpid");

// 	$eventarr = array();
// 	while($eventrow = mysql_fetch_assoc($corpEventQuery)) {
// 		if(!empty($eventrow['coverimage_url']))
// 			$eventrow['coverimage_url'] = $base_url.'/'.$eventrow['coverimage_url'];
// 		$eventarr[] = $eventrow;
// 	}
// 	$eventobj['eventobj'] = $eventarr;
// 	$eventobj['eventcnt'] = count($eventarr);
// 	return $eventobj;
    
    $base_url = "test/";
    $eventobj = array();
    $eventarr = array();
    $eventrow = array();
    $corpEventQuery = json_decode(Event::getEvents('2'));
    //return $corpEventQuery;
    foreach($corpEventQuery as $obj)
    {
        if(!empty($obj->coverimage_url))
        {
            $eventrow['cover_url'] = $base_url.'/'.$obj->coverimage_url;
        }
        $eventarr[] = $eventrow; 
    } 
    $eventobj['eventobj'] = $eventarr;
    $eventobj['eventcnt'] = count($eventarr);
    return $eventobj;
    }
}
