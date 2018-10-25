<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\UserProfileFormRequest;
use Image;

class UserProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $profileinfo = User::find(Auth::user()->id)->profile;
        // dd($profile);
        $files = Storage::files('avatars');
        // dd($files);

        // dd($profile);
        $companyinfo = User::find(Auth::user()->id)->group->company;
        $userinfo = User::find(Auth::user()->id);
        // $profileinfo = User::find(Auth::user()->id)->profile;
        return view('user.profile.edit', compact('profileinfo','userinfo','companyinfo'));
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
    public function update(UserProfileFormRequest $request)
    {
        //
        // dd($request);
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
            // $profile->avatar = Storage::disk('public')->putFile('avatars', $request->file('avatar'));
            $image = $request->file('avatar');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            Image::make($image)->resize(300, 300)->save( storage_path('app/public/avatars/' . $filename ) );
            $profile->avatar = 'avatars/'.$filename;
        }

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

        return back()->with('status', 'Profile updated.');
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
