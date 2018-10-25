<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Auth;
class PagesController extends Controller
{
    //
    public function home()
    {
    	$email = User::find(Auth::user()->id)->email;
    	$fullname = User::find(Auth::user()->id)->profile->fullname;
        return view('backend.home',compact('email','fullname'));
    }
}
