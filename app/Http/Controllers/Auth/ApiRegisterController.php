<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\UserProfile;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use App\Company;

class ApiRegisterController extends Controller
{
    //
    use RegistersUsers;

    protected $redirectTo = '/home';
    public function __construct()
    {
        $this->middleware('guest');
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

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        if(Company::find($request['company_id'])) {
        	$user = User::create([
            'name' => $request['first_name'].' '.$request['last_name'],
            'email' => $request['email'],
            'password' => bcrypt($request['password']),
	        ]);

	        // $user = new User(array(
	        //     'name' => $data['first_name'].' '.$data['last_name'],
	        //     'email' => $data['email'],
	        //     'password' => Hash::make($data['password']),
	        // ));

	        // $user->save();

	        // $profile = new UserProfile(array(
	        //     'user_id'=> $user->id,
	        //     'first_name' => $data['first_name'],
	        //     'last_name' => $data['last_name']
	        // ));
	        // $profile->save();

	        $profile = UserProfile::create([
	            'user_id'=> $user->id,
	            'first_name' => $request['first_name'],
	            'last_name' => $request['last_name']
	        ]);

	        $user->group()->create(['company_id'=>$request['company_id'],'group_id'=>0,'cluster_id'=>0,'account_valid'=>1]);

	        // return $user;

	        // $this->guard()->login($user);
	        // $success['token'] = $user->createToken('nfce_client')->accessToken;
	        $response['user'] = $user;
			// $response['message'] = trans('main.verify');        
	        // return response()->json($success, 201);
	        // return tupe_prepareResult($status, $response, $error['errors'],"success");
	        return tupe_prepareResult(true, $response, [],trans('main.verify'));
        } else {
        	return tupe_prepareResult(false, [], "Company id error","Company id does not exist.");
        }

        
    }

}
