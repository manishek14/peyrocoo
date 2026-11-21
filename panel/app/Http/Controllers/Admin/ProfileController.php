<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Constant;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends BaseController
{
    public function index()
    {
        $admin = auth()->guard('admin')->user();
        return view('admin.profile.index', compact('admin'));
    }

    public function update(Request $request)
    {
        $admin = auth()->guard('admin')->user();
        $data = $this->getData($request);
        $admin = $admin->update($data);

        if ($admin) {
            return redirect()->route('admin.profile.index')->with("store", "ویرایش پروفایل شما با موفقیت انجام شد  ");
        }
        return redirect()->back();
    }

   

   
    private function validateupdateform($request)
    {
        $request->validate([
            'f_name' => ['required'],
            'l_name' => ['required'],
            'phone' => ['required'],
            'email' => ['required'],
            'username' => ['required'],
            'password' => ['required'],
        ], [
            "f_name.required" => "وارد کردن این فیلد الزامیاست ",
            "l_name.required" => "وارد کردن این فیلد الزامیاست ",
            "phone.required" => "وارد کردن این فیلد الزامیاست ",
            "username.required" => "وارد کردن این فیلد الزامیاست ",
            "password.required" => "وارد کردن این فیلد الزامیاست ",
            "email.required" => "وارد کردن این فیلد الزامیاست ",

        ]);
    }

    private function getData(Request $request): array
    {
        $data = [
            'first_name' => $request->input('f_name'),
            'last_name' => $request->input('l_name'),
            'mobile' => $request->input('phone'),
            'email' => $request->input('email'),
            'username' => $request->input('username'),
            'password' => Hash::make($request->input('password')),
        ];
        if ($request->hasFile('avatar')) {
            $data['image'] = $this->uploadFile($request->file('avatar'), Constant::ADMINS_IMAGE_PATH);
        }
        return $data;
    }
}
