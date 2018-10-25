<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        // $uid = Auth::user()->id;
        // $profile = User::find(Auth::user()->id)->profile;
        // Log::info('DashboardController::index: UserId: '.Auth::user()->id);
        $today = Carbon::now()->toDateString();
        $yesterday = Carbon::yesterday()->toDateString();
        $dashboardinfo = User::find(Auth::user()->id)->dashboardInfo($today);

     
    

        // Log::info('DashboardController::index: dashboardinfo: '.print_r($dashboardinfo,true));
        $companyinfo = User::find(Auth::user()->id)->group->company;
        $userinfo = User::find(Auth::user()->id);
        $profileinfo = User::find(Auth::user()->id)->profile;
        $email = $userinfo->email;
        $fullname = $profileinfo->fullname;
        $dpmsdata = User::find(Auth::user()->id)->dpms_completedata($today);
        //return $dpmsdata;
        $stepsdataonly = array_column($dpmsdata, 'steps');
        
        $stepsdataonly2 = array();
        foreach($dpmsdata as $data)
        {
            if(array_key_exists("steps",$data))
            {
             array_push($stepsdataonly2,$data['steps']);
            }
            else
            {
                array_push($stepsdataonly2,$data['totalsteps']);
            }      
        }
        $stepsdataonly =  $stepsdataonly2;

   

        $caloriedataonly = array_column($dpmsdata, 'calories');



        $caloriedataonly2 = array();
        foreach($dpmsdata as $data)
        {
            if(array_key_exists("calories",$data))
            {
             array_push($caloriedataonly2,$data['calories']);
            }
            else
            {
                array_push($caloriedataonly2,$data['totalcalc']);
            }      
        }
        $caloriedataonly =  $caloriedataonly2;


        $ddistfloordata = User::find(Auth::user()->id)->ddistfloor_completedata($today);
        $distdataonly = array_column($ddistfloordata, 'distmeters');
        $stepstostring = implode(",", $stepsdataonly);
        $calorietostring = implode(",", $caloriedataonly);
        $finaldistdataonly = array();
        foreach($distdataonly as $distrow) {
            $finaldistdataonly[]=number_format($distrow/1000,1);
        }
        $disttostring = implode(",", $finaldistdataonly);
        Log::info('DashboardController::index: dpmsdata: '.print_r($dpmsdata,true));
        Log::info('DashboardController::index: ddistfloordata: '.print_r($ddistfloordata,true));
        Log::info('DashboardController::index: stepsdataonly: '.print_r($stepsdataonly,true));
        Log::info('DashboardController::index: caloriedataonly: '.print_r($caloriedataonly,true));
        Log::info('DashboardController::index: distdataonly: '.print_r($distdataonly,true));

        $dashboardinfo['stepstostring'] = $stepstostring;
        $dashboardinfo['calorietostring'] = $calorietostring;
        $dashboardinfo['disttostring'] = $disttostring;
        $dpmstodaykey = array_search($today, array_column($dpmsdata, 'datestr'));

     

        $dpmsydaykey = array_search($yesterday, array_column($dpmsdata, 'datestr'));
        $ddistfloortodaykey = array_search($today, array_column($ddistfloordata, 'datestr'));
        $ddistfloorydaykey = array_search($yesterday, array_column($ddistfloordata, 'datestr'));
        $dashboardinfo['stepsdatasum'] = number_format(array_sum($stepsdataonly),0);
        $dashboardinfo['caloriedatasum'] = number_format(array_sum($caloriedataonly),0);
        $dashboardinfo['distdatasum'] = number_format(array_sum($finaldistdataonly),0);

       



 

        


        $test = array();
        foreach( $dpmsdata as $data)
        {
            if(array_key_exists ('totalsteps' ,$data ))
            {
                $json = str_replace(array("totalsteps","totalcalc"),array("steps", "calories"), json_encode($data));
              //  $json = str_replace('totalcalc', 'calories', json_encode($data));
                $test2 = json_decode($json,true); 
                array_push($test,$test2);
                
                
            }
            else
            {
                array_push($test,$data);  
            }
        }    
        $dpmsdata = $test; 
 
   
     //  return $dpmsdata;   
              
       
        $dashboardinfo['steps'] = array('today'=>$dpmsdata[$dpmstodaykey]['steps'],'yesterday'=>$dpmsdata[$dpmsydaykey]['steps']);
       // return $dpmsdata[$dpmstodaykey]; 
     //   $dashboardinfo['steps'] = array('today'=>$dpmsdata[$dpmstodaykey]->steps,'yesterday'=>$dpmsdata[$dpmsydaykey]->steps);
         

        $dashboardinfo['calories'] = array('today'=>$dpmsdata[$dpmstodaykey]['calories'],'yesterday'=>$dpmsdata[$dpmsydaykey]['calories']);
        $dashboardinfo['dist'] = array('today'=>$ddistfloordata[$ddistfloortodaykey]['distmeters'],'yesterday'=>$ddistfloordata[$ddistfloorydaykey]['distmeters']);

        $avatar = User::find(Auth::user()->id)->profile->avatar ? asset('storage').'/'.User::find(Auth::user()->id)->profile->avatar : asset('storage').'/'.'avatars/avatar.png';
      
    
      
        return view('layouts.dashboard',compact('dashboardinfo','companyinfo','email','fullname','avatar'));
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
}
