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





use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class LaravelToken extends Controller
{
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
		}
		
		
	}
}
