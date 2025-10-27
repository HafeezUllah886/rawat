<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\warehouses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class usersController extends Controller
{
    public function index()
    {
        if(auth()->user()->role == 2)
        {
            return redirect('/sales');
        }
        $users = User::where('id', "!=", auth()->user()->id)->get();
        $warehouses = warehouses::all();

        return view('settings.users', compact('users', 'warehouses'));
    }

    public function store(request $req)
    {
        $check = User::where('email', $req->email)->count();
        if($check > 0)
        {
            return back()->with('error', 'User Already Exists');
        }

        $req->merge(
            [
                'lang' => 'en',
                'password' => Hash::make($req->password)
            ]
        );
        User::create($req->all());

        return back()->with('success', 'User Created');
    }

    public function update(request $req)
    {
        $check = User::where('email', $req->email)->where('id', '!=', $req->id)->count();
        if($check > 0)
        {
            return back()->with('error', 'User Already Exists');
        }

        $req->merge(
            [
                'password' => Hash::make($req->password)
            ]
        );
        User::find($req->id)->update($req->all());

        return back()->with('success', 'User Updated');
    }



}
