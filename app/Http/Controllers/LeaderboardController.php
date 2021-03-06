<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Company;

class LeaderboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $leaderboard = array();
        if(User::find(Auth::user()->id)->group) {

            $leaderboard = User::find(Auth::user()->id)->group->company->programleaderboard();
            $companyinfo = User::find(Auth::user()->id)->group->company;
            $userinfo = User::find(Auth::user()->id);
            $profileinfo = User::find(Auth::user()->id)->profile;
            $email = $userinfo->email;
            $fullname = $profileinfo->fullname;
            $avatar = User::find(Auth::user()->id)->profile->avatar ? asset('storage').'/'.User::find(Auth::user()->id)->profile->avatar : asset('storage').'/'.'avatars/avatar.png';
        }
        
        // Company::find($corpid->id)->programleaderboard(1);
        return view('layouts.leaderboard',compact('leaderboard','companyinfo','email','fullname','avatar'));
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
