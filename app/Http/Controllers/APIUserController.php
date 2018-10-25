<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Validator;
use Image;
use Config;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use App\User;
use App\UserProfile;
use App\UserGroup;
use App\UserPref;
use App\UserDevice;
use App\Company; 
use App\CompanyCode;
use App\UserRewardsInfo;
use App\Points;
use Illuminate\Support\Facades\Log;

class APIUserController extends Controller
{
    //
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

        if($type == "user dashboard"){
            $validator = Validator::make($request->all(),[
                'date' => 'required|date_format:Y-m-d',
            ]);
            if($validator->fails()){
                   $error = true;
                   $errors = $validator->errors();

            } 
        }
       	else if($type == "user update"){
            $validator = Validator::make($request->all(),[
                'user_id' => 'required',
                'first_name' => 'min:3|max:100',
                'last_name' => 'min:3|max:100',
                'weight' => 'numeric',
                'height' => 'numeric',
                'city' => 'min:2',
                'country' =>'min:2',
                'gender' => 'integer',
                'birthdate' =>'date_format:Y-m-d'
            ]);
            if($validator->fails()){
                   $error = true;
                   $errors = $validator->errors();

            } 
        }
        else if($type == "user dailygoal_put"){
            $validator = Validator::make($request->all(),[
                'type' => [
			        'required',
			        Rule::in(['dailygoal']),
			    ],
                'value' => 'required|array',
            ]);
            if($validator->fails()){
                   $error = true;
                   $errors = $validator->errors();

            } 
        }
        else if($type == "user avatar_update"){
            $validator = Validator::make($request->all(),[
                'user_id' => 'required',
                'avatar'=> 	'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
                // 'avatar' =>  ['required',Rule::dimensions()->maxWidth(2000)->maxHeight(1000)],//->ratio(3 / 2)],
            ]);
            if($validator->fails()){
                   $error = true;
                   $errors = $validator->errors();

            } 
        }
        else if($type == "user dailygoal_get"){
        	$validator = Validator::make($request->all(), [
			    'type' => [
			        'required',
			        Rule::in(['dailygoal']),
			    ],
			]);
			/*
            $validator = Validator::make($request->all(),[
                'type' => ['required',Rule::in(['dailygoal',])],
            ]);
            */
            if($validator->fails()){
                   $error = true;
                   $errors = $validator->errors();

            } 
        }
        else if($type == "user group_put"){
            $validator = Validator::make($request->all(),[
                'user_id' => 'required',
                'company_id' => 'required',
                'cluster_id' => 'required',
                'group_id' => 'required'
            ]);
            if($validator->fails()){
                   $error = true;
                   $errors = $validator->errors();

            } 
        }
        else if($type == "user mymo_put"){
            $validator = Validator::make($request->all(),[
                'device_serial' => 'required',
                'user_idx' => 'required',
                'device_type' => 'required',
            ]);
            if($validator->fails()){
                   $error = true;
                   $errors = $validator->errors();

            } 
        }
        else if($type == "user activitymymo_put"){
            $validator = Validator::make($request->all(),[
                'data_type' => 'required',
                'device_imei' => 'required',
                'device_serial' => 'required',
                'user_idx' => 'required',
                'dataArr' => 'required',
            ]);
            if($validator->fails()){
                   $error = true;
                   $errors = $validator->errors();

            } 
        }
        else if($type == "user activity3rd_put"){
            $validator = Validator::make($request->all(),[
                'data_type' => 'required',
                'device_imei' => 'required',
                'device_serial' => 'required',
                'apitoken' => 'required',
                'tokensecret' => 'required',
                'dataArr' => 'required',
            ]);
            if($validator->fails()){
                   $error = true;
                   $errors = $validator->errors();

            }
        }

        return ["error" => $error,"errors"=>$errors];
   }

   /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateprofile(Request $request)
    {
        //
        // dd($request);
        $error = $this->validations($request,"user update");
        if($error['error']) 
        {
            // return $this->prepareResult(false, [], $error['errors'],"error in updating data");
            return tupe_prepareResult(false, [], $error['errors'],"missing parameters");
        } else 
        {
            if($request['user_id'] == Auth::user()->id) {
                $profile = User::find(Auth::user()->id)->profile;
                $profile->first_name = $request['first_name'];
                $profile->last_name = $request['last_name'];

                if($request->has('weight')) {
                    $profile->weight = $request['weight'];
                }

                if($request->has('height')) {
                    $profile->height = $request['height'];
                    $profile->steplength  = $request['height'] * 0.46;  // steplength computation
                }
                
                if($request->has('avatar')) {
                	$image = $request->file('avatar');
          			$filename = time() . '.' . $image->getClientOriginalExtension();
          			Image::make($image)->resize(300, 300)->save( storage_path('app/public/avatars/' . $filename ) );
          			$profile->avatar = 'avatars/'.$filename;
                    // $profile->avatar = Storage::disk('public')->putFile('avatars', $request->file('avatar'));
                }

                $avatarfullpath = $profile->avatar ? asset('storage').'/'.$profile->avatar : asset('storage').'/'.'avatars/avatar.png';
                $profile['avatar'] = $avatarfullpath;

                if($request->has('weightPref')) {
                    $profile->weight_pref = $request['weightPref'];
                }

                if($request->has('heightPref')) {
                    $profile->height_pref = $request['heightPref'];
                }

                if($request->has('gender')) {
                    $profile->gender = $request['gender'];
                }
                
                if($request->has('city')) {
                    $profile->city = $request['city'];
                }

                if($request->has('country')) {
                    $profile->country = $request['country'];
                }
                
                if($request->has('birthdate')) {
                    $profile->birthdate = $request['birthdate'];
                }
                
                
                $profile->save();

                return tupe_prepareResult(true, $profile, [],"success");
            }

            return tupe_prepareResult(false, [], "unauthorized","You are not authorized access this resource.");
        }
            
    
    }

     /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateuserdailygoal(Request $request)
    {
        //
        // dd($request);
        $error = $this->validations($request,"user dailygoal_put");
        if($error['error']) 
        {
            // return $this->prepareResult(false, [], $error['errors'],"error in updating data");
            return tupe_prepareResult(false, [], $error['errors'],"missing parameters");
        } else 
        {
            // if($request['user_id'] == Auth::user()->id) {
            $prefs = User::find(Auth::user()->id)->prefs;
            $newgoals = $request['value'];
            if($prefs) {  
                $prefs->saveserialize($newgoals);
                
            } else {
                $prefs = new UserPref(array(
                    'user_id' =>Auth::user()->id,
                    'type' => 'dailygoal' 
                ));

                $prefs->saveserialize($newgoals);
            }
            
            $prefs->save();

            return tupe_prepareResult(true, $prefs, [],"success");
            // }

            // return tupe_prepareResult(false, [], "unauthorized","You are not authorized access this resource.");
        }
            
    
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateavatar(Request $request)
    {
          $error = $this->validations($request,"user avatar_update");
        if($error['error']) 
        {
            // return $this->prepareResult(false, [], $error['errors'],"error in updating data");
            return tupe_prepareResult(false, [], $error['errors'],"missing parameters");
        } else 
        {
            if($request['user_id'] == Auth::user()->id) {
                $profile = User::find(Auth::user()->id)->profile;
               
                
                if($request->has('avatar')) {
                
					$image = $request->file('avatar');
          			$filename = time() . '.' . $image->getClientOriginalExtension();
          			Image::make($image)->resize(300, 300)->save( storage_path('app/public/avatars/' . $filename ) );
          			$profile->avatar = 'avatars/'.$filename;
                    
                    // $profile->avatar = Storage::disk('public')->putFile('avatars', $request->file('avatar'));
                    
                }

                // $profile->avatar = $profile->avatar ? asset('storage').'/'.$profile->avatar : asset('storage').'/'.'avatars/avatar.png';
                //$profile['avatar'] = $avatarfullpath;

                
                $profile->save();

                $profile['avatar'] = $profile->avatar ? asset('storage').'/'.$profile->avatar : asset('storage').'/'.'avatars/avatar.png';

                return tupe_prepareResult(true, $profile, [],"success");
            }

            return tupe_prepareResult(false, [], "unauthorized","You are not authorized access this resource.");
        }
    }
     /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updategroupinfo(Request $request) 
    {
        $error = $this->validations($request,"user group_put");
        if($error['error']) 
        {
            // return $this->prepareResult(false, [], $error['errors'],"error in updating data");
            return tupe_prepareResult(false, [], $error['errors'],"missing parameters");
        } else 
        {
            if($request['user_id'] == Auth::user()->id && $company = Company::find($request['company_id'])) {
                $user_id = $request['user_id'];
                $cluster_id = $request['cluster_id'];
                $group_id = $request['group_id'];
                $company_id = $request['company_id'];

                if($company->isclusterenabled()) {
                    if(!$company->maingroups()->find($cluster_id)) {
                        return tupe_prepareResult(false, [], "Cluster id error","Cluster id does not exist.");
                    }
                }

                if(!$company->subgroups()->find($group_id)) {
                    return tupe_prepareResult(false, [], "Group id error","Group id does not exist.");
                }
               
                $groupinfo = UserGroup::updateOrCreate(
                    ['user_id' => $user_id], 
                    ['company_id' => $company_id,'cluster_id'=> $cluster_id,'group_id'=>$group_id]
                );
                return tupe_prepareResult(true, $groupinfo, [],"success");
            }
            else {
                return tupe_prepareResult(false, [], "Company id error","Company id does not exist.");
            }
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updatemymo(Request $request) 
    {
        $error = $this->validations($request,"user mymo_put");
        if($error['error']) 
        {
            // return $this->prepareResult(false, [], $error['errors'],"error in updating data");
            return tupe_prepareResult(false, [], $error['errors'],"missing parameters");
        } else 
        {
        	$mymoexist = UserDevice::where('serial',hmm_devices_encode_serial($request['device_serial']))->exists();
            if($mymoexist) {
            	// Log::info('APIUserController:updatemymo: '. $mymoexist);
            	if($mymoinfo = UserDevice::where([['serial',hmm_devices_encode_serial($request['device_serial'])],['user_id',Auth::user()->id]])->first()) {

            		// Log::info('mymo is owned by this user: '.Auth::user()->id);
            		$mymoinfo = UserDevice::updateOrCreate(['user_id'=>Auth::user()->id,'devicetype'=>$request['device_type']],['serial'=>$request['device_serial'],'userindex'=>$request['user_idx']]);
            	// UserDevice::updateOrCreate(['user_id'=>Auth::user()->id,'serial'=>hmm_devices_encode_serial($request['device_serial'])],['serial'=>hmm_devices_encode_serial($request['device_serial']),'userindex'=>$request['user_idx'],'devicetype'=>$request['device_type']]);
            		return tupe_prepareResult(true, $mymoinfo, [],"success");
            	} else {
            		return tupe_prepareResult(false, [], "PUT mymo error","mymo serial number currently owned by other user.");
            	}
            }
            else {
            	$mymoinfo = UserDevice::updateOrCreate(['user_id'=>Auth::user()->id,'devicetype'=>$request['device_type']],['serial'=>$request['device_serial'],'userindex'=>$request['user_idx']]);
                return tupe_prepareResult(true, $mymoinfo, [],"success");
            }
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function userdailygoal(Request $request) 
    {
        $error = $this->validations($request,"user dailygoal_get");
        if($error['error']) 
        {
            // return $this->prepareResult(false, [], $error['errors'],"error in updating data");
            return tupe_prepareResult(false, [], $error['errors'],"missing parameters");
        } else 
        {
            // if($request['user_id'] == Auth::user()->id) {
            // if($request['user_id'] == Auth::user()->id && $request['company_id']) {
            $presfarr = array('dailygoal'=>array('steps'=>0,'calories'=>0,'distance'=>0,'floors'=>0));
            $prefs = User::find(Auth::user()->id)->prefs;
            if($prefs) {
                $presfarr['dailygoal'] = $prefs->ofdailygoal()->first()->unserialize;
            }
            

            return tupe_prepareResult(true, $presfarr,[],"success");
            // }

            // return tupe_prepareResult(false, [], "unauthorized","You are not authorized access this resource.");
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function personaldashboard(Request $request) 
    {
        $error = $this->validations($request,"user dashboard");
        if($error['error']) 
        {
            // return $this->prepareResult(false, [], $error['errors'],"error in updating data");
            return tupe_prepareResult(false, [], $error['errors'],"missing parameters");
        } else 
        {
        
            $dashboardinfo = User::find(Auth::user()->id)->dashboardInfo($request['date']);
            
            return tupe_prepareResult(true, $dashboardinfo,[],"success");
            // }

            // return tupe_prepareResult(false, [], "unauthorized","You are not authorized access this resource.");
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getmymodevice(Request $request) 
    {
        $deviceinfo = User::find(Auth::user()->id)->devices()->whereIn('devicetype',['100','101'])->first();
        if($deviceinfo) {
        	$decoded = $deviceinfo->serialdecoded;
        	$deviceinfo = $deviceinfo->toArray();
            $deviceinfo['serial'] = $decoded;
        }
        return tupe_prepareResult(true, $deviceinfo, [],"success");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function data_mymo(Request $request)
    {
        //
        // dd($request);

       
 
        $error = $this->validations($request,"user activitymymo_put");
        if($error['error']) 
        {
            // return $this->prepareResult(false, [], $error['errors'],"error in updating data");
            return tupe_prepareResult(false, [], $error['errors'],"missing parameters");
        } else 
        {
           
        $uid = Auth::user()->id;
        $user = User::find($uid);
        $profile = UserProfile::where('user_id',$uid)->get();
        $dataType = $request['data_type'];//$data->dataType;
        $imei = $request['device_imei'];//$data->imei;
        $serial = $request['device_serial'];//$data->serial;
        $userIdx = $request['user_idx'];//$data->userIdx;
        // $userID = $data->userid;
        $dataArr = $request['dataArr'];//$data->data;

        
        // $curDate = date('Y-m-d H:i');
        $curDate = Carbon::now();
        
        // $dbcon->doQuery("Update devices SET LastTransmission='$curDate' WHERE Serial='$imei' AND UserIndex = '0'");
        // update the device with this imei number
        $phoneDevice = UserDevice::updateOrCreate(
            ['serial' => $imei,'userindex'=> $userIdx],
            ['user_id'=>$uid,'lasttransmission' => Carbon::now()->toDateTimeString(),'lastseen' => Carbon::now()->toDateTimeString()]
        );

        $dataDevice = UserDevice::updateOrCreate(['user_id'=>Auth::user()->id,'devicetype'=>$dataType],['serial'=>$serial,'userindex'=>$userIdx]);
        
        // $dbcon->doQuery("Update devices SET LastTransmission='$curDate' WHERE Serial='$serial' AND UserIndex = '$userIdx'");
        
       
        
        // $uid = $userID;
        /*
        if($dataType == 111){
          $tid = 0;
          foreach($dataArr as $gps){
            
            $dateTime = $dbcon->getMySqlString($gps->timestamp);
            $dateTime = date('Y-m-d H:i:s', $dateTime);
            
            if($tid = 0){
              $res = $dbcon->doQuery("SELECT * FROM training WHERE UserID = '$uid' AND DateTimestamp = '$dateTime'");
              if(mysql_fetch_array($res)){
                break;
              }
              $dbcon->doQuery("INSERT INTO training (UserID, DateTimestamp) VALUES('$uid', '$dateTime'");
              $tid = mysql_insert_id($dbcon->con);
            }
            
            
            $res = $dbcon->doQuery("SELECT * FROM d_gps WHERE UserID = '$uid' AND DateTimestamp = '$dateTime'");
            if(!mysql_fetch_array($res)){
              $lat = $dbcon->getMySqlString($gps->Latitude);
              $long = $dbcon->getMySqlString($gps->Longitude);
              $dbcon->doQuery("INSERT INTO d_gps (UserID, DateTimestamp, Latitude, Longitude, TrainingID) VALUES('$uid', '$dateTime', '$lat', '$long', '$tid')");
            }
            
            //$startDate += $interval;
          }
        }
        else if($dataType == 120){
          //$serial = hmm_devices_decode_serial_hrm($serial);
          $startDate = $dbcon->getMySqlString($data->dateTime);
          $interval = $dbcon->getMySqlString($data->interval);
          
          $dateTime = date('Y-m-d H:i:s', $startDate);
          
          $res = $dbcon->doQuery("SELECT * FROM training WHERE UserID = '$uid' AND DateTimestamp = '$dateTime'");
          
          if(!mysql_fetch_array($res)){
            
            $dbcon->doQuery("INSERT INTO training (UserID, DateTimestamp) VALUES('$uid', '$dateTime'");
            
            $tid = mysql_insert_id($dbcon->con);
            
            foreach($dataArr as $hr){
            
              $dateTime = date('Y-m-d H:i:s', $startDate);
              
              if(is_array($hr) || is_object($hr)){
                $hr = $hr->HartRate;
                $lat = $hr->Lat;
                $long = $hr->Long;
                
                $res = $dbcon->doQuery("SELECT * FROM d_hrm WHERE UserID = '$uid' AND DateTimestamp = '$dateTime'");
                if(!mysql_fetch_array($res)){
                  $hr = $dbcon->getMySqlString($hr);
                  $dbcon->doQuery("INSERT INTO d_hrm (UserID, DateTimestamp, HeartRate, Variance, MET, Calories, SerialNumber, TrainingID) VALUES('$uid', '$dateTime', '$hr', '0', '0', '0', '$serial', '$tid')");
                }
                
                $res = $dbcon->doQuery("SELECT * FROM d_gps WHERE UserID = '$uid' AND DateTimestamp = '$dateTime'");
                if(!mysql_fetch_array($res)){
                  $lat = $dbcon->getMySqlString($lat);
                  $long = $dbcon->getMySqlString($long);
                  $dbcon->doQuery("INSERT INTO d_gps (UserID, DateTimestamp, Latitude, Longitude, TrainingID) VALUES('$uid', '$dateTime', '$lat', '$long', '$tid')");
                }
              }
              else{
                $res = $dbcon->doQuery("SELECT * FROM d_hrm WHERE UserID = '$uid' AND DateTimestamp = '$dateTime'");
                if(!mysql_fetch_array($res)){
                  $hr = $dbcon->getMySqlString($hr);
                  $dbcon->doQuery("INSERT INTO d_hrm (UserID, DateTimestamp, HeartRate, Variance, MET, Calories, SerialNumber, TrainingID) VALUES('$uid', '$dateTime', '$hr', '0', '0', '0', '$serial', '$tid')");
                }
              }
              $startDate += $interval;
            }
          }
        }
        else if($dataType == 119){
          foreach($dataArr as $rec){
            $weight = $dbcon->getMySqlString($rec->weight);
            $hydration = $dbcon->getMySqlString($rec->hydration);
            $bodyFat = $dbcon->getMySqlString($rec->bodyFat);
            $boneMass = $dbcon->getMySqlString($rec->boneMass);
            $muscleMass = $dbcon->getMySqlString($rec->muscleMass);
            $amr = $dbcon->getMySqlString($rec->amr);
            $bmr = $dbcon->getMySqlString($rec->bmr);
            $dateTime = $dbcon->getMySqlString($rec->dateTime);
            
            $timeMod = $dateTime % 60;
            $dateTime = $dateTime - $timeMod;
            
            $dateTime = date('Y-m-d H:i', $dateTime);
            
            $res = $dbcon->doQuery("SELECT * FROM d_ws WHERE UserID='$uid' AND DateTimestamp = '$dateTime'");
            
            if(!mysql_fetch_array($res)){
              $dbcon->doQuery("INSERT into d_ws(UserID,BodyWeight,Hydration,BodyFat,MuscleMass,BoneMass,ActiveMetabolicRate,BasalMetabolicRate,DateTimestamp,SerialNumber) VALUES('$uid','$weight','$hydration','$bodyFat','$muscleMass','$boneMass','$amr','$bmr','$dateTime','$serial')");
            }
            
            
          }
        }
        */
        // else if($dataType == 100 || $dataType == 101){
        /* 09.07.2018. Currently support mymo only, if you want to support the weighing scale device then get the code above where it has condition for dataType == 119
        */
        if($dataType == 100 || $dataType == 101){
          // $usertzq = $dbcon->doQuery("SELECT timezone_name from drupal.users where uid=$uid");
          // if($tzrow = mysql_fetch_assoc($usertzq)) {
          //   $usertz = $tzrow['timezone_name'];
          //   date_default_timezone_set($tzname);
          // } 
          if(!empty($profile->timezone_name)) {
            date_default_timezone_set($profile->timezone_name);
          }

          $prevdpm = '';
          // $deletedotherdatatoday = FALSE;
          $runquery = FALSE;
          foreach($dataArr as $rec){
            /*
            $steps = $dbcon->getMySqlString($rec->steps);
            $met = $dbcon->getMySqlString($rec->met);
            $calories = $dbcon->getMySqlString($rec->calories);
            $dateTime = $dbcon->getMySqlString($rec->dateTime);
            */
            $steps = $rec['steps'];
            $met = $rec['met'];
            $calories = $rec['calories'];
            $dateTime = $rec['dateTime'];
            /*$dateTime = intval($dateTime);
            
            $timeMod = $dateTime % 60;
            $dateTime = $dateTime - $timeMod;
            
            $dateTime = date('Y-m-d H:i', $dateTime);*/
            // $datetime2 = strtotime($dateTime);
            $datetime2 = Carbon::parse($dateTime);
            // $datetime2format = Carbon::parse($dateTime)->toDateString();
            $datetime2format = Carbon::parse($dateTime)->toDateString();
            if($datetime2format != Carbon::today()->toDateString()) { 
              if($prevdpm == '') {
                $found = 0;
                $prevdpm = $datetime2format;
              }
              else {
                if($found && $prevdpm == $datetime2format) {
                  continue; // if the current dpm date is equal to prevoius processed dpm where it already exist with steps data from 3rd party
                }
                if($prevdpm != $datetime2format) {
                  $found = 0;
                }
              }
              // check if serialnumber for existing dpm is 'Health Kit','Fitbit','Google Fit','Garmin','S Health'
              /*
              $selectotherdevicepm = $dbcon->doQuery("SELECT id from hmm.d_pm where userid=$uid and serialnumber IN('7065646f6d657465720d','666974626974','676f6f676c65666974','4741524d494e','534845414c5448') and date(datetimestamp) = '$datetime2format'");
              */
              if($user->dpms()->ofDatefromthirdparty($datetime2format)->get()->count() > 0) {
                $found = 1;
                $prevdpm = $datetime2format;
                continue;     // do not add current dpm when date has already dpm with 3rd party data
              }
            } else {
              // check if serialnumber for existing dpm is 'Health Kit','Fitbit','Google Fit','Garmin','S Health'
              if(!$runquery) {
                /*
                $selectotherdevicepm = $dbcon->doQuery("SELECT id from hmm.d_pm where userid=$uid and serialnumber IN('7065646f6d657465720d','666974626974','676f6f676c65666974','4741524d494e','534845414c5448') and date(datetimestamp) = '$datetime2format'");
                */
                $dpms_del = $user->dpms()->ofDatefromthirdparty($datetime2format)->get();
                if($dpms_del->count() > 0) {
                  foreach ($dpms_del as $a_dpms) {
                    # code...
                    $user->dpms()->findOrfail($a_dpms->id)->delete();
                  }
                  // $idtodelete = $otherdatarow['id'];
                  // $dbcon->doQuery("DELETE from hmm.d_pm where id=$idtodelete");
                  // $deletedotherdatatoday = TRUE;
                }
                $runquery = TRUE;
              }
              
            }
            // $res = $dbcon->doQuery("SELECT * FROM d_pm WHERE UserID='$uid' AND DateTimestamp = '$dateTime'");
            
            if(!$user->dpms()->ofDatetime($datetime2->toDateTimeString())->exists()) 
            {
              $user->dpms()->create([
                'numberofsteps'=>$steps,
                'datetimestamp'=>$datetime2->toDateTimeString(),
                'met'=>$met,
                'calcalories'=>$calories,
                'serialnumber'=>hmm_devices_encode_serial($serial),
                'isaerobic'=>0,
                'isprocessed'=>0]);
            }
            /*
            if(!mysql_fetch_array($res)){
              $dbcon->doQuery("INSERT into d_pm(UserID,NumberOfSteps,DateTimestamp,MET,calcCalories,SerialNumber) VALUES('$uid','$steps','$dateTime','$met','$calories','$serial')");
            }
            */
          }
        } 

        return tupe_prepareResult(true, [], [],"success");
            // }

            // return tupe_prepareResult(false, [], "unauthorized","You are not authorized access this resource.");
        }
            
    
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function data_thirdparty(Request $request)
    {
        //
        // dd($request);
        $error = $this->validations($request,"user activity3rd_put");
        if($error['error']) 
        {
            // return $this->prepareResult(false, [], $error['errors'],"error in updating data");
            return tupe_prepareResult(false, [], $error['errors'],"missing parameters");
        } else 
        {
          //watchdog('debug', '<pre>'. print_r($data, TRUE) .'</pre>');
          //$data = json_decode($data);
          $uid = Auth::user()->id;
          $user = User::find($uid);
          $profile = UserProfile::where('user_id',$uid)->get();
          $dataType = $request['data_type'];//$data->dataType;
          $imei = $request['device_imei'];//$data->imei;
          $serial = $request['device_serial'];//$data->serial;
          $apitoken = $request['apitoken'];
          $apitype = $request['apitype'];
          $tokensecret = $request['tokensecret'];
          // $userID = $data->userid;
          $dataArr = $request['dataArr'];//$data->data;

          
          // $curDate = date('Y-m-d H:i');
          $curDate = Carbon::now();
          // $uid = 0;
          // error_log("dataArr 3rdparty: ".print_r($data,true).PHP_EOL,3,"/tmp/dpm.log");
          // $dataType = $data->dataType;
          // $imei = $data->imei;
          // $serial = $data->serial;
          // $userid = $data->userid;
          // $dataArr = $data->data;
          // $apitoken = $data->apitoken;
          // $apitype = $data->apitype;
          // $tokensecret = $data->tokensecret;
          $arrret = array();

          // module_load_include('php', 'hmm_activities', 'dbconnector');
          // module_load_include('php', 'hmm_activities', 'constants');
          // // module_load_include('module', 'hmm_corpchallenge_services', 'hmm_corpchallenge_service');
          // require_once '/var/www/sites/all/modules/hmm_corpchallenge_services/hmm_corpchallenge_service.inc';
          // include_once '/var/www/sites/all/modules/hmm_corpchallenge_services/library/hmm_corpchallenge_service.module';
          // $dbcon = new DbConnector();
          
        //   $imei = $dbcon->getMySqlString($imei);
        //   $serial = $dbcon->getMySqlString($serial);
        // //  $userIdx = $dbcon->getMySqlString($userIdx);
        //   $uid = $dbcon->getMySqlString($userid);
        //   $apitype = $dbcon->getMySqlString($apitype);
        //   $apitoken = $dbcon->getMySqlString($apitoken);
        //   $tokensecret = $dbcon->getMySqlString($tokensecret);
          $curDate = date('Y-m-d H:i');
          
          // error_log("add_data_thirdparty V2 start\n",3,"/tmp/dpm.log");
          // error_log("data: ".print_r($data,true).PHP_EOL,3,"/tmp/dpm.log");
          // +26.07.2017. Change $uid==0 with content load
          /*
          if($uid == 0){
            return 0;
          }*/
          // $account = user_load($uid);
          // if (empty($account)) {
          //     // return services_error('Registration failed. Profile data issue.', 403);
          // return 0;;
          // } 
          if(!empty($profile->timezone_name)) {
            date_default_timezone_set($profile->timezone_name);
          }

          $deviceUpdate = UserDevice::updateOrCreate(
            ['serial' => $imei,'user_id'=> $uid],
            ['user_id'=>$uid,'devicetype'=>200,'lasttransmission' => Carbon::now()->toDateTimeString(),'lastseen' => Carbon::now()->toDateTimeString()]
          );

          // $deviceinfo = $dbcon->doQuery("SELECT * from hmm.devices where UserID='$uid' and Serial='$serial'");
          // if(mysql_num_rows($deviceinfo) > 0) {
          //   $dbcon->doQuery("Update devices SET LastTransmission='$curDate' WHERE Serial='$serial' AND UserID = '$uid'");
          // } else {
          //   $dbcon->doQuery("INSERT INTO hmm.devices (UserID,Serial,UserIndex,DeviceType,LastTransmission) VALUES ($uid,'$serial',0,200,'$curDate')");
          // }
          
          // if($apitype == API_TYPE_FITBIT || $apitype == API_TYPE_JAWBONE) {
          if($apitype == Config::get('constants.apitype.API_TYPE_FITBIT') || $apitype == Config::get('constants.apitype.API_TYPE_JAWBONE')) {
            $apitypestr = '';
            switch($apitype) {
              case Config::get('constants.apitype.API_TYPE_FITBIT'):
              //  error_log("this is fitbit data\n",3,"/tmp/dpm.log");
                $apitypestr = Config::get('constants.tokentype.TOKEN_TYPE_FITBIT');
              break;
              case Config::get('constants.apitype.API_TYPE_JAWBONE'):
              //  error_log("this is jawbone data\n",3,"/tmp/dpm.log");
                $apitypestr = Config::get('constants.tokentype.TOKEN_TYPE_JAWBONE');
              break;
            }

            $userdevicetoken = UserDevice::updateOrCreate(
              ['type' => $apitypestr,'user_id'=> $uid],
              ['user_id'=>$uid,'type'=>$apitypestr,'accesstoken' =>$apitoken ,'tokensecret' => $tokensecret]
            );
            
            // $tokeninfo = $dbcon->doQuery("SELECT * from drupal.thirdparty_access where uid='$uid'");
            // if(mysql_num_rows($tokeninfo) > 0) {
            // //  error_log("updating accesstoken in thirdparty_access\n",3,"/tmp/dpm.log");
            //   $dbcon->doQuery("UPDATE drupal.thirdparty_access SET accesstoken='$apitoken',type='$apitypestr' where uid='$uid'");
            // } else {
            // //  error_log("inserting accesstoken in thirdparty_access\n",3,"/tmp/dpm.log");
            //   $dbcon->doQuery("INSERT INTO drupal.thirdparty_access (uid,accesstoken,type) VALUES ($uid,'$apitoken','$apitypestr')");
            // }
            // if($apitype == API_TYPE_FITBIT && !empty($tokensecret)) {
            //   $dbcon->doQuery("UPDATE drupal.thirdparty_access SET tokensecret='$tokensecret',type='$apitypestr' where uid='$uid'");
            // }
            
          }
          //return print_r($data, true);
          
          // error_log("userid: $uid".PHP_EOL,3,"/tmp/dpm.log");
          // error_log("this is the dataArr\n",3,"/tmp/dpm.log");
          // error_log(print_r($dataArr,true)."\n",3,"/tmp/dpm.log");
          $maxdate = '';
          
          if($dataType == 100 || $dataType == 101){
            Log::info('APIUserController::dataType: '.$dataType);
            // error_log("datatype 101\n",3,"/tmp/dpm.log");
            foreach($dataArr as $rec){
              $steps = $rec['steps'];
              $met = $rec['met'];
              $calories = $rec['calories'];
              $dist_mtrs = $rec['distance'];
              $floorcnt = $rec['floors'];
              $dateTime = $rec['dateTime'];
              Log::info('APIUserController::rec: '.print_r($rec,true));
            //  $dateTime = intval($dateTime);
              $dateTime = strtotime($dateTime);
              if(!empty($maxdate)) {
                if($dateTime > $maxdate) {
                  $maxdate = $dateTime;
                }
              } else {
                $maxdate = $dateTime;
              }
              
              $steps = intval($steps);
              $calories = floatval($calories);
              $dist_mtrs = floatval($dist_mtrs);
              $floorcnt = intval($floorcnt);
              $timeMod = $dateTime % 60;
              $dateTime = $dateTime - $timeMod;
              $dateTimeExist = 0;
              // +rodel.21.01.2016. Delete the existing steps rows in a given datetime that is coming from a mymo device
              if($steps > 0) {
                Log::info('APIUserController::steps > 0');
                $dateTimeNew = date('Y-m-d H:i', $dateTime);
                $dateTimeNew2 = date('Y-m-d', $dateTime);
                Log::info('APIUserController::dateTimeNew2: '. $dateTimeNew2);
                $todayts = strtotime('today midnight');
                /* 28.04.2016. Removing the deletion of row */
        //        $dbcon->doQuery("DELETE from hmm.d_pm where userid='$uid' and date(datetimestamp)='$dateTime' and serialnumber <> '666974626974' and serialnumber <> '6a6177626f6e65' and serialnumber <> '7065646f6d657465720d0a'");
                // +rodel.21.01.2016. Delete the existing row(s) first before inserting 
                // $res = $dbcon->doQuery("SELECT * FROM d_pm WHERE UserID='$uid' AND date(DateTimestamp) = '$dateTimeNew2' order by datetimestamp desc LIMIT 1");
                $thisdpms = $user->dpms()->ofDate($dateTimeNew2)->first();
                if($thisdpms) { //check if null or not
                  $dateTimeExist = 1;
                }
                // if dataArray->dateTime is not equal to today's date
                if($dateTimeNew2 != date('Y-m-d')) {
                  if($dateTimeExist == 1) {
                    // $oldserial = $dpmrow['SerialNumber'];
                    $oldserial = $thisdpms->serialnumber;
                    // $oldsteps = $dpmrow['NumberOfSteps'];
                    $oldsteps = $thisdpms->numberofsteps;
                    if($oldserial == substr($serial,0,20)) {
                        // $dbcon->doQuery("UPDATE d_pm SET NumberOfSteps=$steps, calcCalories=$calories,DateTimestamp='$dateTimeNew',IsProcessed=0 where UserID='$uid' and DateTimestamp='$dateTimeNew'"); 
                          $thisdpms->isprocessed = 0;
                          $thisdpms->calcalories = $calories;
                          $thisdpms->numberofsteps = $steps;
                          $thisdpms->save();
                        // error_log("UPDATE d_pm SET NumberOfSteps='$steps',DateTimestamp='$dateTimeNew',IsProcessed=0 where UserID='$uid' and DateTimestamp='$dateTimeNew'".PHP_EOL,3,"/tmp/dpm.log");
                      // }
                      // $checkDistFloor = $dbcon->doQuery("SELECT * FROM d_distFloor WHERE UserID='$uid' AND date(DateTimestamp) = '$dateTimeNew2' order by datetimestamp desc LIMIT 1");

                      $thisDistFloor = $user->ddistflors()->ofDate($dateTimeNew2)->first();
                      if($thisDistFloor) {
                        // error_log("UPDATE d_distFloor SET dist_meters=$dist_mtrs, floorcnt=$floorcnt,DateTimestamp='$dateTimeNew',IsProcessed=0 where UserID='$uid' and DateTimestamp='$dateTimeNew'\n",3,"/tmp/dpm.log");
                        // $dbcon->doQuery("UPDATE d_distFloor SET dist_meters=$dist_mtrs, floorcnt=$floorcnt,DateTimestamp='$dateTimeNew',IsProcessed=0 where UserID='$uid' and DateTimestamp='$dateTimeNew'");
                        $thisDistFloor->isprocessed = 0;
                        $thisDistFloor->distmeters = $dist_mtrs;
                        $thisDistFloor->floorcnt = $floorcnt;
                        $thisDistFloor->save();
                      } else {
                        // $dbcon->doQuery("INSERT INTO d_distFloor(UserID,dist_meters,floorcnt,datetimestamp,SerialNumber) VALUES('$uid',$dist_mtrs,$floorcnt,'$dateTimeNew','$serial')");
                          $user->ddistflors()->create([
                            'distmeters'=>$dist_mtrs,
                            'datetimestamp'=>$dateTimeNew,
                            'floorcnt'=>$floorcnt,
                            'serialnumber'=>$serial,
                            'isprocessed'=>0
                          ]);
                      }
                    } // end condition if serial==oldserial
                  } else {
                    // error_log("dateTime does not exist in d_pm".PHP_EOL,3,"/tmp/dpm.log");
                    // +rodel.29.05.2016. If new data->datetime does not exist in d_pm then check the previous d_pm row's serial number is the same serial number as the new data
                    // query for the previous d_pm of the new data->datetimestamp
                    // $res = $dbcon->doQuery("SELECT * FROM d_pm WHERE UserID='$uid' AND date(DateTimestamp) < '$dateTimeNew2' ORDER BY DateTimestamp DESC LIMIT 1");

                    $prevdpm = $user->dpms()->wheredate('datetimestamp','<',$dateTimeNew2)->orderby('datetimestamp','desc')->first();
                    if($prevdpm) {
                      // check if the previous dpm serial is the same as new data->datetimestamp serial
                      $oldserial = $prevdpm->serialnumber;
                      $olddpmdts = $prevdpm->datetimestamp;
                      // error_log("previous d_pm dts: $olddpmdts".PHP_EOL,3,"/tmp/dpm.log");
                      // error_log("new data serial: $serial; old data serial: $oldserial".PHP_EOL,3,"/tmp/dpm.log");
          //            error_log("old serial: $oldserial".PHP_EOL,3,"/tmp/dpm.log");
          //            error_log("new serial: ".substr($serial,0,20).PHP_EOL,3,"/tmp/dpm.log");
                      if($oldserial == substr($serial,0,20)) {
          //              error_log("old serial number equals to new serial".PHP_EOL,3,"/tmp/dpm.log");
                        // error_log("steps are not the same will do update".PHP_EOL,3,"/tmp/dpm.log");
                        // $dbcon->doQuery("INSERT INTO d_pm(UserID,NumberOfSteps,DateTimestamp,MET,calcCalories,SerialNumber) VALUES('$uid',$steps,'$dateTimeNew','$met',$calories,'$serial')");
                        $user->dpms()->create([
                          'numberofsteps'=>$steps,
                          'datetimestamp'=>$dateTimeNew,
                          'met'=>$met,
                          'calcalories'=>$calories,
                          'serialnumber'=>$serial,
                          'isaerobic'=>0,
                          'isprocessed'=>0
                        ]);
                        // $dbcon->doQuery("INSERT INTO d_distFloor(UserID,dist_meters,floorcnt,datetimestamp,SerialNumber) VALUES('$uid',$dist_mtrs,$floorcnt,'$dateTimeNew','$serial')");
                  //      $dbcon->doQuery("INSERT INTO d_pm (UserID,NumberOfSteps,DateTimestamp,IsProcessed) VALUES ('$uid','$steps','$dateTimeNew',0)");
                        $user->ddistflors()->create([
                          'distmeters'=>$dist_mtrs,
                          'datetimestamp'=>$dateTimeNew,
                          'floorcnt'=>$floorcnt,
                          'serialnumber'=>$serial,
                          'isprocessed'=>0
                        ]);
                      } 
                    } else {
                      // +rodel.03.12.2016. Add d_pm entry if user's d_pm profile is empty and the new row is equal to or greater than program_startdate
                      /*
                      $program = $dbcon->doQuery("SELECT t1.userid,t2.orgid,t2.program_startdate from drupal.users_groups t1, drupal.company t2 where t1.userid=$uid and t1.orgid=t2.orgid");

                      if($programrow = mysql_fetch_assoc($program)) {
                        $programs_date = $programrow['program_startdate'];
                        // +rodel.03.11.2016. Condition if the new dpm row is greater than or equal to program start date then add the new row
                        if(strtotime($dateTimeNew2) >= strtotime(date('Y-m-d',strtotime($programs_date)))) {
                          $dbcon->doQuery("INSERT INTO d_pm(UserID,NumberOfSteps,DateTimestamp,MET,calcCalories,SerialNumber) VALUES('$uid','$steps','$dateTimeNew','$met','$calories','$serial')");
                          // error_log("INSERT INTO d_distFloor(UserID,dist_meters,floorcnt,datetimestamp,SerialNumber) VALUES('$uid',$dist_mtrs,$floorcnt,'$dateTimeNew','$serial')\n",3,"/tmp/dpm.log");
                          $dbcon->doQuery("INSERT INTO d_distFloor(UserID,dist_meters,floorcnt,datetimestamp,SerialNumber) VALUES('$uid',$dist_mtrs,$floorcnt,'$dateTimeNew','$serial')");
                        }
                      } */
                      
                      $programs_date = Company::find($user->group()->first()->company_id)->program_startdate;
                      if(strtotime($dateTimeNew2) >= strtotime(date('Y-m-d',strtotime($programs_date)))) {
                        $user->dpms()->create([
                          'numberofsteps'=>$steps,
                          'datetimestamp'=>$dateTimeNew,
                          'met'=>$met,
                          'calcalories'=>$calories,
                          'serialnumber'=>$serial,
                          'isaerobic'=>0,
                          'isprocessed'=>0
                        ]);
                        $user->ddistflors()->create([
                          'distmeters'=>$dist_mtrs,
                          'datetimestamp'=>$dateTimeNew,
                          'floorcnt'=>$floorcnt,
                          'serialnumber'=>$serial,
                          'isprocessed'=>0
                        ]);
                      }
                    } // else user's dpm is empty
                    // -rodel.29.05.2016
                  } // else datime not exist
                } else {
                  // error_log("dateTime is today's date. delete and update steps".PHP_EOL,3,"/tmp/dpm.log");
                  $user->dpms()->ofDate($dateTimeNew2)->delete();
                  $user->dpms()->create([
                    'numberofsteps'=>$steps,
                    'datetimestamp'=>$dateTimeNew,
                    'met'=>$met,
                    'calcalories'=>$calories,
                    'serialnumber'=>$serial,
                    'isaerobic'=>0,
                    'isprocessed'=>0
                  ]);
                  $user->ddistflors()->ofDate($dateTimeNew2)->delete();
                  $user->ddistflors()->create([
                    'distmeters'=>$dist_mtrs,
                    'datetimestamp'=>$dateTimeNew,
                    'floorcnt'=>$floorcnt,
                    'serialnumber'=>$serial,
                    'isprocessed'=>0
                  ]);
                  // $dbcon->doQuery("DELETE from hmm.d_pm where userid='$uid' and date(datetimestamp)='$dateTimeNew2'");
                  
                  // $dbcon->doQuery("DELETE from hmm.d_distFloor where userid='$uid' and date(datetimestamp)='$dateTimeNew2'");
                  
                  
                  // $dbcon->doQuery("INSERT INTO d_distFloor(UserID,dist_meters,floorcnt,datetimestamp,SerialNumber) VALUES('$uid',$dist_mtrs,$floorcnt,'$dateTimeNew','$serial')");
        //          error_log("INSERT INTO d_pm(UserID,NumberOfSteps,DateTimestamp,MET,calcCalories,SerialNumber) VALUES('$uid','$steps','$dateTimeNew','$met','$calories','$serial')".PHP_EOL,3,"/tmp/dpm.log");
                } // end else today's date
              } // steps > 0
            } // foreach
            
          } // datetype == 100 || datetype 101
          /*
          $corpinfo = $dbcon->doQuery("SELECT orgid from drupal.users_groups where userid=$uid");
          if($usercorpinfo = mysql_fetch_assoc($corpinfo)) {
            $corpid = $usercorpinfo['orgid'];
            $datestr = date('Y-m-d',$maxdate);
         
            $arrret['dashboardinfo'] = hmm_challenge_service_getpersonaldashboardV2($uid, $corpid, $datestr);
            
          }
          */
          $arrret['dashboardinfo'] = User::find(Auth::user()->id)->dashboardInfo($request['date']);
          /*
          $challengestream = array();
          $challengestream = hmm_challenge_service_getchallengestream($uid, $corpid, $datestr);
          $arrret['challengestream'] = $challengestream;
          */
          // return $arrret;
          return tupe_prepareResult(true, $arrret, [],"success");
            // }

            // return tupe_prepareResult(false, [], "unauthorized","You are not authorized access this resource.");
        }
            
    
    }


    function userrewards(Request $request)
    {
        /*
        global $user;
        global $base_url;
        module_load_include('php', 'hmm_activities', 'dbconnector');
        $dbcon = new DbConnector();
        
        $rewardsinfo = array();
        $transferred = 0;
        $earned = 0;
        $donated = 0;
        $rewardsacctnum = '';        
        $corpinfoquery = $dbcon->doQuery("SELECT * from drupal.company where orgid=$corpid");
        if($corpinforow = mysql_fetch_assoc($corpinfoquery)) {
            $program_startdate = $corpinforow['program_startdate'];
            $program_enddate = $corpinforow['program_enddate'];
        }
        if(!isset($program_startdate) || !isset($program_enddate)) {
            return services_error('Corp id not recognized', 403);
        }
        $strstartdate = date('Y-m-d',strtotime($program_startdate));
        $strenddate = date('Y-m-d',strtotime($program_enddate));
        
        $useracctnum = $dbcon->doQuery("SELECT * from drupal.user_rewards_info where userid=$userid");
        if($useracctrow = mysql_fetch_assoc($useracctnum)) {
            $rewardsacctnum = $useracctrow['reward_acct_id'];
        }
        
        $currentprogramearned = $dbcon->doQuery("SELECT ifnull(sum(points),0) 'earned' from hmm.points where userid=$userid and type <> 50 and date(datetimestamp) >= '$strstartdate'");
        if($currentprogramdata = mysql_fetch_assoc($currentprogramearned)) {
            $earned = $currentprogramdata['earned'];
        }
        $donatedPointsQ = $dbcon->doQuery("SELECT ifnull(sum(points),0) 'donatedpts' from hmm.points where userid=$userid and type <> 50 and date(datetimestamp) >= '$strstartdate' and DATE_FORMAT(datetimestamp,'%M %Y') = 'October 2016'");
        if($donatedPtsdata = mysql_fetch_assoc($donatedPointsQ)) {
            $donated = $donatedPtsdata['donatedpts'];
        }    
        $transferredresult = $dbcon->doQuery("SELECT 
        t1.uid,
       ifnull(sum(miles),0) 'transferred'
    from
        drupal.convertedmiles t1,
        (SELECT 
            id, datetimestamp, points 'ptsMiles'
        from
            hmm.points
        where
            userid = $userid and type <> 50
                and date(datetimestamp) >= '$strstartdate'
                and DATE_FORMAT(datetimestamp, '%M %Y') <> 'October 2016'
        order by datetimestamp) t2
    where
        t1.pointsid = t2.id and t1.status = 1");
        if($transferredrow = mysql_fetch_assoc($transferredresult)) {
            $transferred  = $transferredrow['transferred'];
        }        
        $earnptsarray = array();
        if($earned > 0) {
            $userpointsinfo = $dbcon->doQuery("SELECT points,date(datetimestamp) 'ptsdate',type 'code' from hmm.points where userid=$userid and type<>50 and date(datetimestamp) >= '$strstartdate' and date(datetimestamp) <= '$strenddate' ORDER BY datetimestamp");
            while($earnedrow = mysql_fetch_assoc($userpointsinfo)) {
                $earnptsarray[] = array("points"=>$earnedrow['points'],"ptsdate"=>$earnedrow['ptsdate'],"ptscode"=>$earnedrow['code']);
            }
        }
          $rewardsinfo['rewardsacctinfo'] = array("rewards_acct_header_text"=>'Etihad Guest Number',"useracctnum"=>$rewardsacctnum);
        $rewardsinfo["earned"] = $earned;
        $rewardsinfo["transferred"] = $transferred;
        $rewardsinfo["donated"] = "0";
        $rewardsinfo["earned_pts_table"] = $earnptsarray;
        $corpImgCampaign = $dbcon->doQuery("SELECT * from drupal.corp_image_rewardscampaign where corpid=$corpid");
        $imgcampaigns = array();
        while($campaignrow = mysql_fetch_assoc($corpImgCampaign)) {
            $campaignpath = $base_url .'/'.$campaignrow['path'];
            $imgcampaigns[]= $campaignpath;
        }
        $rewardsinfo["campaign_img_urls"] = $imgcampaigns;  
        return $rewardsinfo;
*/

//$user_id,$company_id

$userid = $request['user_id'];
$corpid =$request['company_id'];


$rewardsinfo = array();
$transferred = 0;
$earned = 0;
$donated = 0;
$rewardsacctnum = '';       






//$corpinfoquery = $dbcon->doQuery("SELECT * from drupal.company where orgid=$corpid");
$corpinfoquery = Company::where('id',$corpid)->first();
if($corpinfoquery) 
{
    $program_startdate = $corpinfoquery['program_startdate'];
    $program_enddate = $corpinfoquery['program_enddate'];
}




if(!isset($program_startdate) || !isset($program_enddate)) {
    return services_error('Corp id not recognized', 403);
}
$strstartdate = date('Y-m-d',strtotime($program_startdate));
$strenddate = date('Y-m-d',strtotime($program_enddate));
//$useracctnum = $dbcon->doQuery("SELECT * from drupal.user_rewards_info where userid=$userid");
$useracctnum = UserRewardsInfo::where('user_id',$userid)->first();

if($useracctnum)
{
    $rewardsacctnum = $useracctnum['reward_acct_id'];
}
$currentprogramearned = Points::currentProgramEarned($userid,$strstartdate);
if($currentprogramearned) {
    $earned = $currentprogramearned['earned'];
}








//$donatedPointsQ = $dbcon->doQuery("SELECT ifnull(sum(points),0) 'donatedpts' from hmm.points where userid=$userid and type <> 50 and date(datetimestamp) >= '$strstartdate' and DATE_FORMAT(datetimestamp,'%M %Y') = 'October 2016'");

$donatedPointsQ = Points::donatedPoints($strstartdate);
if($donatedPtsdata = $donatedPointsQ) 
{
    $donated = $donatedPtsdata['donatedpts'];
}    





/*
$transferredresult = $dbcon->doQuery("SELECT 
t1.uid,
ifnull(sum(miles),0) 'transferred'
from
drupal.convertedmiles t1,
(SELECT 
    id, datetimestamp, points 'ptsMiles'
from
    hmm.points
where
    userid = $userid and type <> 50
        and date(datetimestamp) >= '$strstartdate'
        and DATE_FORMAT(datetimestamp, '%M %Y') <> 'October 2016'
order by datetimestamp) t2
where
t1.pointsid = t2.id and t1.status = 1");
*/

$transferredresult = Points::transferedResults($userid,$strstartdate);


if($transferredrow = $transferredresult) {
    $transferred  = $transferredrow['transferred'];
}        


$earnptsarray = array();
if($earned > 0) {
    //$userpointsinfo = $dbcon->doQuery("SELECT points,date(datetimestamp) 'ptsdate',type 'code' from hmm.points where userid=$userid and type<>50 and date(datetimestamp) >= '$strstartdate' and date(datetimestamp) <= '$strenddate' ORDER BY datetimestamp");
  
    $userpointsinfo = Points::userpointsinfo($userid,$strstartdate,$strenddate);
  
  
    while($earnedrow = $userpointsinfo) {
        $earnptsarray[] = array("points"=>$earnedrow['points'],"ptsdate"=>$earnedrow['ptsdate'],"ptscode"=>$earnedrow['code']);
    }
}


$rewardsinfo['rewardsacctinfo'] = array("rewards_acct_header_text"=>'Etihad Guest Number',"useracctnum"=>$rewardsacctnum);
$rewardsinfo["earned"] = $earned;
$rewardsinfo["transferred"] = $transferred;
$rewardsinfo["donated"] = "0";
$rewardsinfo["earned_pts_table"] = $earnptsarray;


//  $corpImgCampaign = $dbcon->doQuery("SELECT * from drupal.corp_image_rewardscampaign where corpid=$corpid");

$corpImgCampaign = Points::where('company_id',$corpid)->first();

$imgcampaigns = array();
while($campaignrow = mysql_fetch_assoc($corpImgCampaign)) {
    $campaignpath = $base_url .'/'.$campaignrow['path'];
    $imgcampaigns[]= $campaignpath;
}
$rewardsinfo["campaign_img_urls"] = $imgcampaigns;  
return $rewardsinfo;











    }

    function updaterewardsnum(Request $request)
    {

        
        $user_id = Auth::user()->id;
        //$user_id = $request['uid'];
        $acctnum = $request['account_number'];
        $company_id = $request['company_id'];           
        $useracctnum = UserRewardsInfo::where('user_id',$user_id)->first();
       

        if($useracctnum) 
        {
           // $status = $dbcon->doQuery("UPDATE drupal.user_rewards_info SET reward_acct_id='$_acctnum' WHERE userid=$userid");
           $useracctnum->user_id = $user_id;
           $useracctnum->reward_acct_id = $acctnum;
           $useracctnum->company_id = $company_id;
           $status = $useracctnum->save();	          
        } 
        else
         {
            //$status = $dbcon->doQuery("INSERT INTO drupal.user_rewards_info (userid,reward_acct_id) VALUES ($userid,'$_acctnum')");         
            $useracctnum = new UserRewardsInfo;
            $useracctnum->user_id = $user_id;
            $useracctnum->reward_acct_id = $acctnum;
            $useracctnum->company_id = $company_id;
            $status = $useracctnum->save();	
        }
//return json_encode($useracctnum);
    }

    function updatetz(Request $request)
    {
        $uid = $request['uid'];
       // $uid = '3';

        $timezone = $request['timezone'];
        $userstatus = User::select('status')->where('id', $uid)->get();

        if($userstatus == 0) {
            return services_error('2', 403);
        }

        if ($timezone != null or (!empty($timezone))) {

            $userTZ = UserProfile::where('user_id',$uid)->first();
            $userTZ->timezone_name = $timezone;
            $userTZ->save();
            return 1;
        }
        return 0;



/*
        $userstatus = $dbcon->doQuery("SELECT status from drupal.users where uid=$uid");
        if($userrow = mysql_fetch_assoc($userstatus)) {
            if($userrow["status"] == 0) {
                return services_error('2', 403);
            }
        }
        
        if ($timezone != null or (!empty($timezone))) {
            $res = db_query("UPDATE {users} SET timezone_name = '" . $timezone . "' WHERE uid = '" . $uid . "'");
            return 1;
        }
        return 0;
        */
    }




    
}
