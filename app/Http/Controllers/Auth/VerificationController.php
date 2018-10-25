<?php

namespace App\Http\Controllers\Auth;

use App\Events\UserRequestedVerificationEmail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\VerificationTokens;
use App\User;
use Illuminate\Support\Facades\Log;

class VerificationController extends Controller
{
    //
    public function verify(VerificationTokens $token)
    {
    	//
    	/*
		$token->user()->update([
			'verified' => true
		]);
		*/
		// Log::info('VerificationController::verify : token->user_id: '.$token->user_id);
		// $thisuser = User::find($token->user_id)->firstOrFail();
		// $thisuser->verified = true;
		// $thisuser->save();
		User::find($token->user_id)->update(['verified'=>true]);
		$token->delete();
 
	    // Uncomment the following lines if you want to login the user 
	    // directly upon email verification
		// Auth::login($token->user);
	    // return redirect('/home');
	 
		return redirect('/login')->withInfo('Email verification succesful. Please login again');
    }

    public function resend(Request $request)
    {
    	//
    	$user = User::whereEmail($request->email)->firstOrFail();
 
	    if($user->verified) {
	        return redirect('/');
	    }

	    Log::info('VerificationController::resend - '.$user->email);
	 
	    event(new UserRequestedVerificationEmail($user));
	 
	    return redirect('/login')->withInfo('Verification email resent. Please check your inbox');
    }
}
