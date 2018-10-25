<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\User;

class ApiForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }


    public function sendresetpasswordemail(Request $request) 
    {
        // $this->validateEmail($request);
        $validator = Validator::make($request->all(), [
            'email' => 'required',
        ]);

        if ($validator->fails()) 
        {
            $errors = $validator->errors();
            return tupe_prepareResult(false, [], $errors,"Missing required field.");
        }

        if (!User::where('email', $request['email'])->exists()) {
            // exists
             return tupe_prepareResult(false, [], "Email not found","Email not found.");
        }

        // if(!$this->validateEmail($request)) 
        // {
            
        // }
        // $response = $this->broker()->sendResetLink(
        //     $request->only('email')
        // );

        // $response == Password::RESET_LINK_SENT
        //         ? $this->sendResetLinkResponse($response)
        //         : $this->sendResetLinkFailedResponse($request, $response);

        // return tupe_prepareResult(true, $response, [],'Reset password email sent.');

        $this->sendResetLinkEmail($request);
        // return response()->json(null, 204);    
        return tupe_prepareResult(true, [], [],'Reset password email sent.');


        // return $this->sendFailedLoginResponse($request);
    }

    // public function sendResetLinkEmail(Request $request)
    // {
    //     $this->validateEmail($request);

    //     // We will send the password reset link to this user. Once we have attempted
    //     // to send the link, we will examine the response then see the message we
    //     // need to show to the user. Finally, we'll send out a proper response.
    //     $response = $this->broker()->sendResetLink(
    //         $request->only('email')
    //     );

    //     return $response == Password::RESET_LINK_SENT
    //                 ? $this->sendResetLinkResponse($response)
    //                 : $this->sendResetLinkFailedResponse($request, $response);
    // }

}
