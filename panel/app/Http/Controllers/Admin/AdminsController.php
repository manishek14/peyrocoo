<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Constant;
use App\Filters\AdminsFilter;
use App\Presenters\Web\Admin\UserPresenter as WebAdminPresenter;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminsController extends BaseController
{

    public function index()
    {
        $admins = Admin::filter(new AdminsFilter())->paginate();
        return view('admin.admins.all', compact('admins'));
    }

    public function create()
    {

        $activityStatuses = Constant::getStatusesViewer();
        return view('admin.admins.create', compact('activityStatuses'));
    }

    public function store(Request $request)
    {


        $this->validateStoreform($request);
        $data = $this->getData($request);
        $admin = Admin::create($data);
        if ($admin instanceof Admin) {
          
            return redirect()->route('admin.admins.all')->with("store", "عملیات شما با موفقیت انجام شد ");
        }
        return redirect()->back();
    }

    public function edit(Admin $admin)
    {

        $activityStatuses = Constant::getStatusesViewer();
        return view('admin.admins.edit', compact('admin', 'activityStatuses'));
    }

    public function update(Request $request, Admin $admin)
    {

        $this->validateupdateform($request,$admin);

        $data = $this->getData($request);
        $result = $admin->update($data);

        if ($result) {
          
            return redirect()->route('admin.admins.all')->with("store", "ویرایش شما با موفقیت انجام شد  ");
        }
        return redirect()->back();

    }


    public function logicalDeletion(Admin $admin)
    {

        $admin->delete();

        return redirect()->back()->with("delete", 'مدیر شما با موفقیت حذف شد ');
    }

    private function validateStoreform($request)
    {
        $request->validate([
            'f_name' => ['required'],
            'l_name' => ['required'],
            'mobile' => ['required', 'unique:admins'],
            'email' => ['required', 'unique:admins'],
            'username' => ['required','unique:admins'],
            'password' => ['required'],
            'avatar' => ['required'],
           
        ], [
            "f_name.required" => "وارد کردن این فیلد الزامیاست ",
            "l_name.required" => "وارد کردن این فیلد الزامیاست ",
            "mobile.required" => "وارد کردن این فیلد الزامیاست ",
            "username.required" => "وارد کردن این فیلد الزامیاست ",
            "password.required" => "وارد کردن این فیلد الزامیاست ",
            "email.required" => "وارد کردن این فیلد الزامیاست ",
            "avatar.required" => "وارد کردن این فیلد الزامیاست ",
            
        ]);

    }

    private function validateupdateform($request,$admin)
    {
        $request->validate([
            'f_name' => ['required'],
            'l_name' => ['required'],
            'mobile' => ['required', Rule::unique('admins', 'mobile')->ignore($admin)],
            'email' => ['required', 'email', 'unique:users'],
            'username' => ['required', Rule::unique('admins', 'username')->ignore($admin)],
            'password' => ['required'],
          
        ], [
            "f_name.required" => "وارد کردن این فیلد الزامیاست ",
            "l_name.required" => "وارد کردن این فیلد الزامیاست ",
            "mobile.required" => "وارد کردن این فیلد الزامیاست ",
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
            'mobile' => $request->input('mobile'),
            'email' => $request->input('email'),
            'username' => $request->input('username'),
            'password' => Hash::make($request->input('password')),
            'status' => $request->input('status'),
        ];
        if ($request->hasFile('avatar')) {
            $data['image'] = $this->uploadFile($request->file('avatar'), Constant::ADMINS_IMAGE_PATH);
        }
        return $data;
    }


}
