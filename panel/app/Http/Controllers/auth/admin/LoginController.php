<?php

namespace App\Http\Controllers\Auth\admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{

    public function showLoginForm()
    {

        return view('auth.admin.login');
    }

    public function login(Request $request)
    {

        $request->validate([
            'username' => ['required', 'exists:admins'],
            'password' => ['required'],
        ], [
            'username.required' => 'وارد کردن نام کاربری الزامی است ',
            'password.required' => 'وارد کردن پسورد الزامی است ',
        ]);


        $admin = Admin::where('username', $request->input('username'))->first();
        dd($admin);

        if ($admin instanceof Admin) {
            if (!Hash::check($request->input('password'), $admin['password'])) {

                return redirect()->back()->with('login-failed', 'اطلاعات ورود صحیح نمیباشد');
            }

            // login patient

            Auth::guard('admin')->login($admin);
            if($admin->type == "house")
            {
                 return redirect()->route('admin.house-keeping.orders');

            }elseif($admin->type == "coffee-shop")
            {
                 return redirect()->route('admin.coffee-shops.orders');

            }
            else
            {
                 return redirect()->route('admin.dashboard');
            }

        }
        return redirect()->back()->with('login-failed', 'اطلاعات ورود صحیح نمیباشد');

    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('auth.admin.login.form');
    }
}
