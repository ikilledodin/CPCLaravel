<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;


class ApiLoginController extends Controller
{
    //
    use AuthenticatesUsers;

    protected $redirectTo = '/home';
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);

        if ($this->attemptLogin($request)) {
            // $user = Auth::user()::with('profile')::with('group');
            // $user = User::find(Auth::user()->id)::with(['profile','group'])->get();
        	$user = User::find(Auth::user()->id);
        	$response = array();
        	$error = array('errors'=>'');
            if(!$user->verified) {
            	$this->guard()->logout();
     
            // return redirect('/login')
            //     ->withError('Please activate your account. <a href="' . route('auth.verify.resend') . '?email=' . $user->email .'">Resend?</a>');
            	$message = 'Please activate your account. <a href="' . route('auth.verify.resend') . '?email=' . $user->email .'">Resend?</a>';
            	// $code = 403;
            	$status = false;
            	$error['errors'] = 'unverified';
        	} else {
        		$profile = $user->profile;
        		$avatarfullpath = $profile->avatar ? asset('storage').'/'.$profile->avatar : asset('storage').'/'.'avatars/avatar.png';
        		$profile['avatar'] = $avatarfullpath;
        		$group = $user->group;
        		$response['token'] = $user->createToken('HealthQuest')->accessToken;
            	$response['user'] = $user;
            	$message = "success";
            	$status = true;
            	// $response['profile'] = $profile;
            	// $code = 200;
        	}
            
            // return response()->json($response, $code);
            // return tupe_prepareResult($status, $response, $error['errors'],"success");
            return tupe_prepareResult($status, $response, $error['errors'],$message);
        }

        return $this->sendFailedLoginResponse($request);
    }

    public function logout()
    {
        $user = Auth::user();
        $user->token()->revoke();
        $user->token()->delete();

        return response()->json(null, 204);        
    }
}
