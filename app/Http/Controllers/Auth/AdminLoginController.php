<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\AdminResetPassword;

class AdminLoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:admin')->except('logout');
    }

    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $this->validate($request, [
            'email' => 'required',
            'password' => 'required',
        ]);

        if (Auth::guard('admin')->attempt($credentials, $request->remember)) {
            $user = $this->guard()->user();
            if ($user->status==1) {
                return redirect()->intended(route('admin.dashboard'));
            } else {
                $this->guard()->logout();
                return back()->with('error', 'This account is not activated. Please contact the administrator.');
            }
        }

        return back()->withError('Invalid login credentials')->withInput($request->only('email'));
    }

    public function showForgotPasswordForm()
    {
        return view('admin.forgot-password');
    }
    public function validateEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $admin = Admin::where('email', $request->email)->first();

        if (!$admin) {
            return back()->with('error', 'Email address not found.');
        }

        $token = Str::random(60);

        $admin['token'] = $token;
        $admin->save();

        Mail::to($request->email)->send(new AdminResetPassword($admin, $token));

        if (Mail::failures() != 0) {
            return back()->with('success', 'password reset link has been sent to your email.');
        }
        return back()->with('error', 'Something went wrong!!!');
    }

    public function forgotPasswordValidate($token)
    {
        $admin = Admin::where('token', $token)->first();
        if ($admin) {
            $email = $admin->email;
            return view('admin.reset_password', compact('email'));
        }
        return redirect()->route('admin.forgot-password')->with('error', 'Password reset link is expired.');
    }

    public function updatePassword(Request $request)
    {
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        $admin = Admin::where('email', $request->email)->first();
        if ($admin) {
            $admin['token'] = '';
            $admin['password'] = Hash::make($request->password);
            $admin->save();
            return redirect()->route('admin.login')->with('success', 'Password changed successfully.');
        }
        return redirect()->route('admin.forgot-password')->with('error', 'Something went wrong!');
    }

    public function guard()
    {
        return Auth::guard('admin');
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();
        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect(route('admin.login'));
    }
}
