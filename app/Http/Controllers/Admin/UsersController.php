<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Spatie\Permission\Models\Role;
use App\Http\Requests\UserEditFormRequest;
use Illuminate\Support\Facades\Hash;
use Auth;
class UsersController extends Controller
{
    //
    public function index()
	{
	    $users = User::all();
	    $email = User::find(Auth::user()->id)->email;
    	$fullname = User::find(Auth::user()->id)->profile->fullname;
	    return view('backend.users.index', compact('users','email','fullname'));
	}

	public function edit($id)
	{
	    $user = User::whereId($id)->firstOrFail();
	    $roles = Role::all();
	    $selectedRoles = $user->roles()->pluck('name')->toArray();
	    $userinfo = User::find(Auth::user()->id);
    	$profileinfo = User::find(Auth::user()->id)->profile;
    	$email = $userinfo->email;
    	$fullname = ucwords($profileinfo->fullname);
	    return view('backend.users.edit', compact('user', 'roles', 'selectedRoles','email','fullname'));
	}

	public function update($id, UserEditFormRequest $request)
	{
	    $user = User::whereId($id)->firstOrFail();
	    $user->name = $request->get('name');
	    $user->email = $request->get('email');
	    $password = $request->get('password');
	    if($password != "") {
	        $user->password = Hash::make($password);
	    }
	    $user->save();

	    $user->syncRoles($request->get('role'));

	    return redirect(action('Admin\UsersController@edit', $user->id))->with('status', 'The user has been updated!');
	}
}
