<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\RoleFormRequest;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\User;
use Auth;

class RolesController extends Controller
{
    //
    public function index()
	{
	    $roles = Role::all();
	    $email = User::find(Auth::user()->id)->email;
    	$fullname = User::find(Auth::user()->id)->profile->fullname;
	    return view('backend.roles.index', compact('roles','email','fullname'));
	}
	
    public function create()
    {
        return view('backend.roles.create');
    }

    public function store(RoleFormRequest $request)
	{
	    Role::create(['name' => $request->get('name')]);

	    return redirect('/admin/roles/create')->with('status', 'A new role has been created!');
	}
}
