<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        return view('admin.dashboard');
    }

    public function profile()
    {
        return view('admin.profile');
    }

    public function update()
    {
        $user = Auth::user();

        $this->validate(
            request(),
            [
                'name' => 'required|max:255',
                'email' => 'required|email|max:255|unique:admins,email,' . $user->id,
            ],
        );

        $user->update([
            'name' => strip_tags(request('name')),
            'email' => strip_tags(request('email')),
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Profile Updated Successfully.');
    }

    public function showChangePasswordForm()
    {
        return view('admin.change_password');        
    }

    public function change_password()
    {
        $user = Auth::user();

        $this->validate(
            request(),
            [
                'cpassword' => 'required|current_password:admin',
                'password' => 'required|max:255|confirmed',
                'password_confirmation' => 'required',
            ],
        );

        $user->update([
            'password' => bcrypt(strip_tags(request('password'))),
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Password Changed Successfully.');
    }
}
