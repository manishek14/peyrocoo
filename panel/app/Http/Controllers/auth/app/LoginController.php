<?php

namespace App\Http\Controllers\Auth\app;

use App\Constants\Constant;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.app.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'phone' => ['required'],
            'password' => ['required'],
        ]);

        $user = User::where('phone', $request->input('phone'))->first();
        if ($user instanceof User) {

            if (!Hash::check($request->input('password'), $user['password'])) {

                return redirect()->back()->with('login-failed', '   The entered information is not correct');
            }
            // login user
            Auth::guard('web')->login($user);
            return redirect()->route('app.dashboard');
        }
        return redirect()->back()->with('login-failed', 'The entered information is not correct');

    }

    public function logout()
    {
        Auth::guard('web')->logout();
        return redirect()->route('auth.app.login.form');
    }

}
