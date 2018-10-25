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
use App\UserGoal;
use App\VerificationTokens;
use App\CorpinformationStream;

use Twitter;




use App\UserDevice;

// use Illuminate\Foundation\Auth\RegistersUsers;
// use Illuminate\Support\Facades\Validator;

use Illuminate\Foundation\Auth\RegistersUsers;



use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class LaravelApi extends Controller
{
	use RegistersUsers;
    /**
    * Get a validator for an incoming Todo request.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  $type
    * @return \Illuminate\Contracts\Validation\Validator
    */

    public function index(Request $request)
    {
        $method = $request->input('method');
        $client = new \GuzzleHttp\Client();  
  

		$base_url = "35.184.243.145";
        switch($method)
        {     
			case "system.connect":
				$response = $client->post(
					'http://35.184.243.145/oauth/token',
					[
						'form_params' => [
							'grant_type' => 'client_credentials',
							'client_id' => '5',
							'client_secret' => 'mPslkGgxzVFYzLry0nmZANPk4M1huBsjYAVq24VT'
						]
					] 
				);  
				
				$data = json_decode($response->getBody());
				$token = $data->access_token;
				$post_data = json_encode(array('#error' =>"false", '#data'=>array('sessid'=>$token)));	
				return $post_data;
				break;

			case "corpchallenge.getcompanyconfig":		


			$error = $this->validations($request,"company config");
  
        if($error['error']) 
        {
            // return $this->prepareResult(false, [], $error['errors'],"error in updating data");
            return tupe_prepareResult(false, [], $error['errors'],"missing parameters");
        } 
        else 
        {			
		
				$company_code =  $request['companycode'];
				//company code table
				$ccode = CompanyCode::where('company_code',$company_code)->first();
				//company table
		
				$company = Company::find($ccode->company_id);	
				$devicesupport = array();
				$myweightinfo = array();
				$featuresarr = array();
				$groupings = array();
				$splashObj = array();
				$rewardsinfo = array();
				$prefsdefault = 'a:1:{s:8:"distance";s:2:"mi";}';

				$company_config = array();
				$orgid = 0;
				$_companycode = $company_code; 
				$prefs = array();

				$company_config['corpid'] = $ccode['company_id'];
				
			//	$company_config['corpid'] = "1";
				$company_config['corpcode'] = $company_code;
				$company_config['corpname'] = $company['name'];
				//-----$company_config['roleid'] = $orgcoderow['roleid'];
				$company_config['roleid'] = "1";
				$company_config['program_name'] = $company['description'];
				$company_config['primary_color'] = $ccode['app_theme_primary'];
				$company_config['grpmode'] = $company['group_mode'];
				$company_config['clustrmode'] = $company['cluster_mode'];
				$company_config['grp_alias'] = $company['group_alias'];
				$company_config['clustr_alias'] = $company['cluster_alias'];
				$company_config['company_tz'] = $company['tzname'];
				$company_config['prgrm_sdate'] = $company['program_startdate'];
				$company_config['prgrm_edate'] = $company['program_enddate'];
				$company_config['isUsername'] = $ccode['uname_enable'];
				$company_config['isEmailFilter'] = $ccode['register_filter'];
				if($ccode['register_filter'] == 1) 
				{
					$company_config['emaildomainFilter'] = $ccode['email_filter'];
				}		
				//----------	$company_config['web_logo_url'] = $base_url . '/' . $orgcoderow['web_program_logo'];		
				$company_config['web_logo_url'] = $base_url . '/' . $ccode['app_program_logo'];		
				$company_config['app_logo_url'] = $base_url . '/' . $ccode['app_program_logo'];
				$company_config['app_splash_url'] = $base_url . '/' . $ccode['app_splash_screen'];
				$company_config['app_loginimage_url'] = $base_url . '/' . $ccode['app_login_screen'];
				$company_config['features'] = tupe_analyzefeatures($ccode['feature_enable']);
				$company_config['devicesupport'] = tupe_analyzedevsupport($ccode['device_support']);
				
				$company = Company::find($ccode->company_id);
			
				$company_code = $company->code;
				if(isset($company_config['features'][4]['value']) ) 
				{
					$myweight = $company_config['features'][4]['value'];
					$myweightinfo['menu_title'] = 'My Weight';
					if($myweight==1) 
					$myweightinfo['addButtonShow'] = 1;
					else 
					$myweightinfo['addButtonShow'] = 0;
				}
				$company_config['myweightinfo'] = $myweightinfo;
				$company_config['actdataShow'] = tupe_analyzedatashow($ccode['datashow']);

				if(!empty($company_code->prefs->prefs_serialized)) 
				{
					$prefsarr = unserialize($company_code->prefs->prefs_serialized);
				} 
				else 
				{
					$prefsarr = unserialize($prefsdefault);
				}
				$company_config['companyPrefs'] = $prefsarr;

				$groupings = $this->getCompanyGroupings($company['id']);
				$company_config['groupings'] = $groupings;

				$splashObj = $this->getCompanySplashScreen($company_code['app_splash_screen']);
				$company_config['splash_screen_urls'] = $splashObj;		

				$company_config['channels']['channelcount'] = "1";
				$company_config['channels']['channelobj'] = $company->chatchannels()->get();
				//$company_config['channels'] = $company->chatchannels()->get();
				$rewardsinfo = $this->getCompanyRewardsInfo($company['id']);
				$company_config['rewardsinfo'] = $rewardsinfo;
				$company_config['expiry'] = date('Y-m-d', strtotime("+20 days",strtotime($company['program_enddate'])));
				$post_data = json_encode(array('#error' =>"false", '#data'=>$company_config));	
				return $post_data;
				}	
				break;
				
				
				
				case "userV2.login_challengeV2":
					$username = $request['username']; 
					$password = $request['password']; 
					$org_id = $request['corpid']; 
					$user = User::where('name',$username)->first();					
					$usercount = User::where('name',$username)->count();
					
					
					
				//	return $user->verified;
					
					if($usercount == 1)
					{
						if (Hash::check($password, $user->password))  
						{
							if($user->verified == 1)
							{
								$userprofileData = array();						
								$userID = $user->id;
								$userprofile = UserProfile::where('user_id',$userID)->first();
								$usergoal = UserGoal::where('uid',$userID)->first();
								$userGroup = UserGroup::where('user_id',$userID)->first();
								$user_goal_data = array('steps'=>$usergoal->steps,'calories'=>$usergoal->calories,'distance'=>$usergoal->distance,'floors'=>$usergoal->floors);	
								$userprofileData['userId'] = $user->id;
								$userprofileData['userName'] =$user->name;
								$userprofileData['userFname'] = $userprofile->first_name;
								$userprofileData['userLname'] = $userprofile->last_name;
								$userprofileData['userWeight'] = $userprofile->weight;
								$userprofileData['userHeight'] = $userprofile->height;
								$userprofileData['userGender'] = $userprofile->gender;
								$userprofileData['userbirthDate'] = $userprofile->birthdate;
								$userprofileData['userCountry'] = $userprofile->country;
								$userprofileData['userCity'] = $userprofile->city;
								$userprofileData['userZip'] = '111111111';
								$userprofileData['userAvatarURL'] = $userprofile->avatar;
								$userprofileData['groupSet'] = $userGroup->group_id;
								$userprofileData['heightPref'] = $userprofile->height_pref;
								$userprofileData['weightPref'] = $userprofile->weight_pref;	
								$userprofileData['dailygoal'] = $user_goal_data;	
								$userprofileData['userTeam'] = '';	
								$userprofileData['corpId'] = '';	
								$data = array('sessid'=>$usergoal->steps,'user'=>$userprofileData,'fitmeees'=>'');	
								$post_data = json_encode(array('#error' =>"false", '#data'=>$data));									
								return $post_data;
							}
							else
							{
								return json_encode(array('#error' =>"false",'#data'=> array('#error'=>'true','#message'=>'4')));	
							
							//	return json_encode(array('#error'=>'true','#message'=>'4'));	
							
							}
						}
						else
						{
							//return "password";
							//return $user->password."=="$password;
							return json_encode(array('#error' =>"false",'#data'=> array('#error'=>'true','#message'=>'2')));	
						}
					}
					else
					{
						return json_encode(array('#error' =>"false",'#data'=> array('#error'=>'true','#message'=>'2')));	
						//return "username";
					}			
					break;
					
					
					
					
					case "userV2.register_challengeV2":
					
						
						$userCount = User::where('email',$request['email'])->count();
						
						
						
						if($userCount != 1)
						{
							
						 if($request['gender'] == "Male"){$genderCode = 0;}
							else{$genderCode = 1;}	
							$user = User::create([
									 'name' => $request['uname'],
									 'email' => $request['email'],
									 'password' => bcrypt($request['password']),							 
									 ]);
									 
									 $profile = UserProfile::create([
										'user_id'=> $user->id,
										'first_name' => $request['fname'],
										'last_name' => $request['lname'],	 
										'weight' => $request['weight'],	 
										'height' => $request['height'],
										'height_pref' => $request['heightPref'],
										'weight_pref' => $request['weightPref'],
										'gender' => $genderCode,
										'birthdate' => $request['birthdate'],
										'country' => $request['country'],
										'city' => $request['city'],
										'timezone_name' => 'Asia/bahrain'							 
									 ]);
							$user->group()->create(['company_id'=>2,'group_id'=>0,'cluster_id'=>0,'account_valid'=>1]);	
							
							$usergoal = new UserGoal;
								$usergoal->uid = $user->id;
								$usergoal->steps = 0;
								$usergoal->calories = 0;
								$usergoal->distance = 0;
								$usergoal->weight = 0;
								$usergoal->floors = 0;
							$usergoal->save();			

							return json_encode(array('#error' =>"false", '#data' => array('user'=>array('userID'=>$user->id,'userName'=>$user->name,'corpId'=>'1'))));				
						
						 
						}
						else
						{
							return json_encode(array('#error' =>"false", '#data'=>array('#error'=>'true','#message'=>'7')));	
						}//{"#error":false,"#data":{"#error":true,"#message":"7"}}
						
						break;



						case "userV2.addDevice":				
							$rawSerial = $request["serial"];
							$rawDevtype = $request["devType"];
							$rawUser = $request["user"];
							$rawSessid = $request["sessid"];
						//	$activeUser = Auth::user()->id;
							$activeUser = "39";
							$mymoexist = UserDevice::where('serial',hmm_devices_encode_serial($rawSerial))->exists();
							if($mymoexist) 
							{
								if($mymoinfo = UserDevice::where([['serial',hmm_devices_encode_serial($rawSerial)],['user_id',$activeUser]])->first()) 
								{			
									$mymoinfo = UserDevice::updateOrCreate(['user_id'=>$activeUser,'devicetype'=>$rawDevtype],['serial'=>$rawSerial,'userindex'=>$rawUser]);
									//return tupe_prepareResult(true, $mymoinfo, [],"success");
									return json_encode(array('#error' =>"false", '#data' => array('added'=>true,'userID'=>$activeUser)));				
								} 
								else 
								{
								//	return tupe_prepareResult(false, [], "PUT mymo error","mymo serial number currently owned by other user.");
									return json_encode(array('#error' =>"false", '#data' => array('added'=>false,'userID'=>$activeUser)));	
								}
							}
							else 
							{								
								$mymoinfo = UserDevice::updateOrCreate(['user_id'=>$activeUser,'devicetype'=>$rawDevtype],['serial'=>$rawSerial,'userindex'=>$rawUser]);
								//return tupe_prepareResult(true, $mymoinfo, [],"success");
								return json_encode(array('#error' =>"false", '#data' => array('added'=>true,'userID'=>$activeUser)));	
							}					
						break; 

						case "corpchallenge.getpersonaldashboardV2":
							$activeDate = $request['date'];
							$infoNumber = 0;
							$activeUser = "39";
							$dataArray = User::find($activeUser)->dashboardInfo($activeDate);
							$todaySteps = $dataArray['todaySteps'];
							$todayCalories = $dataArray['todayCalories'];
							$todayDist = $dataArray['todayDist'];
							$todayFloor = $dataArray['todayFloor'];

							$yesterdaySteps = $dataArray['yesterdaySteps'];
							$yesterdayCalories = $dataArray['yesterdayCalories'];
							$yesterdayDist = $dataArray['yesterdayDist'];
							$yesterdayFloor = $dataArray['yesterdayFloor'];

							$info = new CorpinformationStream();
							$CID =  UserGroup::select('company_id')->where('user_id',$activeUser)->first();

							$challengeStreamData = new Corpchallenge();
							$challengeID = $challengeStreamData->getChallengeIDdata($activeUser,$CID->company_id,$activeDate);
							$infoStreams = $info->getStreams($CID->company_id);
							$data_merged = array_merge($infoStreams,$challengeID);
							$infoNumber = count($data_merged);

							$data = '
							{  
								"#error":false,
								"#data":{  
								   "dashboardinfo":{  
									  "today":{  
										 "steps":'.$todaySteps.',
										 "calories":'.$todayCalories.',
										 "distance":'.$todayDist.',
										 "floors":'.$todayFloor.'
									  },
									  "yesterday":{  
										 "steps":'.$yesterdaySteps.',
										 "calories":'.$yesterdayCalories.',
										 "distance":'.$yesterdayDist.',
										 "floors":'.$yesterdayFloor.'
									  },
									  "bestsingle":{  
										 "steps":'.$dataArray['bestSteps'].',
										 "calories":'.$dataArray['bestCalories'].',
										 "distance":'.$dataArray['bestDist'].',
										 "floors":'.$dataArray['bestFloor'].'
									  },
									  "total":{  
										 "steps":'.$dataArray['totalSteps'].',
										 "calories":'.$dataArray['totalCalories'].',
										 "distance":'.$dataArray['totalDist'].',
										 "floors":'.$dataArray['totalFloor'].'
									  }
								   },
								   "challengestream":
								   {  
									  "messagecount":0, 
									  "challengemessages":[  
							 
									  ],
									  "infomsgcount":'.$infoNumber.',
									  "infomessages":'.json_encode($data_merged).'
								   }
								}
							 }
							';
							return $data;
						break; 

						case "corpchallenge.getmainleaderboard":

							$rank = 0;
							$leaderboard = User::find($request['userid'])->group->company->programleaderboard();
							$finalarray = array();
							$mySteps = 0;
							$myrank = 0;
							foreach ($leaderboard as $data) 
							{
								$rank++;
								if($data->uid == $request['userid'])
								{
									$mySteps = $data->totalsteps;
									$myrank = $rank;
								}

								$aleaderboard['uid'] = $data->uid;
								$aleaderboard['steps'] = $data->totalsteps;
								$aleaderboard['name'] = $data->fullname;

								if(!$data->avatar)
								{								
									$aleaderboard['photourl'] = "http:\/\/46.101.194.172\/sites\/default\/files\/myPic_423.png";
								}
								else
								{
									$aleaderboard['photourl'] = $data->avatar;
								}		
								array_push($finalarray, $aleaderboard);
							}
							
							$corpchallengeData = Corpchallenge::where('company_id','=',$request['corpid'])->get();
							
							$challengeStartDate = "";
							$challengeEndDate = "";
							foreach($corpchallengeData as $data)
							{
								$challengeStartDate = $data->startdate;
								$challengeEndDate = $data->enddate;
							}

						//	return $corpchallengeData;

							$returnData = '
							{  
								"#error":false,
								"#data":{  
								"message":"heyyy",
								"rank":'.$myrank.',
								"steps":'.$mySteps.',
								"challenge_start":"'.$challengeStartDate.'",
								"challenge_end":"'.$challengeEndDate.'",
								"content":'.json_encode($finalarray).'
								}
							}					
							';
							return $returnData;
						break; 

						case "corpchallenge.getdaychallenges":
								/*
								$leaderboard = array();
								if(User::find(39)->group->company_id == $request['corpid']) {
									$companyinfo = Company::find($request['corpid']);
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
							
									$ret["challenges"] = $challenges;
									return tupe_prepareResult(true, $ret,[],"success");
								} 
								else  
								{
									return tupe_prepareResult(false, [], "unauthorized","You are not authorized to view this resource.");
								}*/
							//	$companyinfo = Company::find($request['corpid']);
					//			$challengelist = $companyinfo->challengelist($request['date']);
						$test = Company::find($request['corpid'])->challengelist2($request['date']);
						$infoNumber = count($test);

						$returndata = '
						{  
							"#error":false,
							"#data":{  
							   "challengecount":'.$infoNumber.',
							   "challenges":'.json_encode($test).'
							}
						 }
						';
						

						$returndata2 = '
						{"#error":false,"#data":{"challengecount":15,"challenges":[{"id":"507","title":"ACTIVE CALORIES CHALLENGE","desc":"This challenge shows the top users who engages most on heart rate pumping activities. Active calories are the actual calories burned from an activity whether it\u00ef\u00bf\u00bds running, brisk walking or cycling. This data is coming from the device of your choice.","imageURL":"http:\/\/46.101.194.172\/sites\/default\/files\/hive\/challenges_logo\/challenge_1516601165.png","challengeweight":"14","category":"Theme Challenge","startdate":"2018-01-01 00:00:00","enddate":"2018-01-14 00:00:00","status":0},{"id":"508","title":"MOST DISTANCE COVERED CHALLENGE","desc":"It\u00ef\u00bf\u00bds all about racking up that mileage as far as you can. The distance challenge shows the top users who\u00ef\u00bf\u00bdve accumulated covered distance whether from the usual commute to the office or your daily run routine. This data is coming from the device of your choice.","imageURL":"http:\/\/46.101.194.172\/sites\/default\/files\/hive\/challenges_logo\/challenge_1516601144.png","challengeweight":"13","category":"Theme Challenge","startdate":"2018-01-01 00:00:00","enddate":"2018-01-21 00:00:00","status":0},{"id":"381","title":"TEAMS  DAILY INDIVIDUAL LEADER","desc":"Can you make it onto the daily leaderboard on your sector?  A new day - A new sector leaderboard!","imageURL":"http:\/\/46.101.194.172\/sites\/default\/files\/hive\/challenges_logo\/2016_1498721846.png","challengeweight":"12","category":"Daily Challenge","startdate":"2018-08-13 00:00:00","enddate":"2018-08-13 23:59:00","status":0},{"id":"412","title":"Most Consistent Challenge","desc":"You will need to be consistent throughout the challenge duration, as steps will only be counted for the days you reach the goal of XXX amount of steps.","imageURL":"http:\/\/46.101.194.172\/sites\/default\/files\/hive\/challenges_logo\/challenge_1498151956.png","challengeweight":"11","category":"Theme Challenge","startdate":"2017-06-18 00:00:00","enddate":"2017-07-02 00:00:00","status":0},{"id":"479","title":"TOP INDIVIDUAL CALORIES BURNER LEADERBOARD","desc":"Shows the top users for number of active calories burned.","imageURL":"http:\/\/46.101.194.172\/sites\/default\/files\/hive\/challenges_logo\/2026_1507790208.png","challengeweight":"10","category":"Daily Challenge","startdate":"2018-08-13 00:00:00","enddate":"2018-08-13 23:59:00","status":0},{"id":"380","title":"COMPANY DAILY INDIVIDUAL LEADER","desc":"If you did not make it onto the daily sector challenge leaderboard?  Mafi Mushkala! Try to make the divisional daily leaderboard..","imageURL":"http:\/\/46.101.194.172\/sites\/default\/files\/hive\/challenges_logo\/2024.png","challengeweight":"9","category":"Daily Challenge","startdate":"2018-08-13 00:00:00","enddate":"2018-08-13 23:59:00","status":0},{"id":"379","title":"HIVE TEAM DAILY LEADERBOARD","desc":"If you did not make it onto the daily sector challenge leaderboard?  Mafi Mushkala! Try to make the divisional daily leaderboard..","imageURL":"http:\/\/46.101.194.172\/sites\/default\/files\/hive\/challenges_logo\/2020_1498721818.png","challengeweight":"8","category":"Daily Challenge","startdate":"2018-08-13 00:00:00","enddate":"2018-08-13 23:59:00","status":0},{"id":"480","title":"TEAM DISPENSING  HIGHEST CALORIES LEADERBOARD","desc":"Shows the top top for number of active calories burned.","imageURL":"http:\/\/46.101.194.172\/sites\/default\/files\/hive\/challenges_logo\/2027_1507790163.png","challengeweight":"7","category":"Daily Challenge","startdate":"2018-08-13 00:00:00","enddate":"2018-08-13 23:59:00","status":0},{"id":"481","title":"LONGEST DISTANCE COVERED DAILY INDIVIDUAL","desc":"Shows the top top for number of distance covered.","imageURL":"http:\/\/46.101.194.172\/sites\/default\/files\/hive\/challenges_logo\/2029_1507791477.png","challengeweight":"6","category":"Daily Challenge","startdate":"2018-08-13 00:00:00","enddate":"2018-08-13 23:59:00","status":0},{"id":"482","title":"LONGEST DISTANCE COVERED BY TEAM DAILY","desc":"Shows the top team for number of distance covered.","imageURL":"http:\/\/46.101.194.172\/sites\/default\/files\/hive\/challenges_logo\/2030_1507792430.png","challengeweight":"5","category":"Daily Challenge","startdate":"2018-08-13 00:00:00","enddate":"2018-08-13 23:59:00","status":0},{"id":"506","title":"FLOORS CHALLENGE","desc":"lol.","imageURL":"http:\/\/46.101.194.172\/sites\/default\/files\/hive\/challenges_logo\/challenge_1510828415.png","challengeweight":"4","category":"Theme Challenge","startdate":"2017-11-12 00:00:00","enddate":"2017-11-30 00:00:00","status":0},{"id":"446","title":"2 Week Challenge","desc":"2 weeks of intense and exciting challenge. The best person will come out at the top.","imageURL":"http:\/\/46.101.194.172\/sites\/default\/files\/hive\/challenges_logo\/challenge_446_1498728654.png","challengeweight":"3","category":"Theme Challenge","startdate":"2017-06-25 00:00:00","enddate":"2017-07-09 00:00:00","status":0},{"id":"509","title":"Male vs Female","desc":"In this Battle of the Sexes we will finally be able to answer the age-old question and see which gender walks more.","imageURL":"http:\/\/46.101.194.172\/sites\/default\/files\/hive\/challenges_logo\/challenge_509_1517381508.png","challengeweight":"3","category":"Theme Challenge","startdate":"2018-01-07 00:00:00","enddate":"2018-01-21 00:00:00","status":0},{"id":"445","title":"The Hive Get Active Group Challenge","desc":"This is the Hive Get Active group challenge. The best group will come out at end of this exciting challenge.","imageURL":"http:\/\/46.101.194.172\/sites\/default\/files\/hive\/challenges_logo\/challenge_445_1498728450.png","challengeweight":"2","category":"Theme Challenge","startdate":"2017-06-26 00:00:00","enddate":"2017-07-09 00:00:00","status":0},{"id":"378","title":"Top Group Battle","desc":"Top Group Battle","imageURL":"http:\/\/46.101.194.172\/sites\/default\/files\/hive\/challenges_logo\/challenge_1496591175.png","challengeweight":"1","category":"Theme Challenge","startdate":"2017-04-16 00:00:00","enddate":"2017-04-23 00:00:00","status":0}]}}
						
						';

						return $returndata2;		
						break;


						case "newmethod":
						$data = Twitter::getUserTimeline(['count' => 10, 'format' => 'array']);
						$tweets = Twitter::getSearch(array('q' => 'ahawalkingchallenge2017','result_type' => 'recent', 'count' => 100, 'format' => 'array'));
						 	return $tweets;

						break;




        }     
    }
	
	
	  
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	public static function getCompanyCode($userID)
	{
		return User::select('company_id')->where('user_id',$userID)->first();
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
                'date' => 'required',
                'company_id' => 'required',
            ]);

            if($validator->fails()){
                   $error = true;
                   $errors = $validator->errors();

            } 
        }

        return ["error" => $error,"errors"=>$errors];
   }

   
      protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'company_id' => 'required',
        ]);
    }

	
	
}
