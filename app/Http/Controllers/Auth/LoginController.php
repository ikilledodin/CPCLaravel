<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function authenticated(Request $request, $user)
    {
        if(!$user->verified) {
            $this->guard()->logout();
     
            return redirect('/login')
                ->withError('Please activate your account. <a href="' . route('auth.verify.resend') . '?email=' . $user->email .'">Resend?</a>');
        }

        if($user->hasRole('HQSuperAdmin')) {
            return redirect()->intended('/admin');
        }

        return redirect()->intended('/dashboard');
    }
}
