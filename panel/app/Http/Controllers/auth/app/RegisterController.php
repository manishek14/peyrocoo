<?php

namespace App\Http\Controllers\Auth\app;

use App\Constants\Constant;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegisterForm()
    {
        return view('auth.app.register');
    }
    public function register(Request $request)
    {

          // validate request
          $this->validateRegisterRequest($request);

        // prepare data to insert in db
          $data = $this->getData();
          // create new user
          $user = User::create($data);

          // redirect
          if ($user instanceof User) {
              Auth::guard('web')->login($user);
              return redirect()->route('app.dashboard');
          }
          return redirect()->back();



    }
    private function validateRegisterRequest(Request $request)
    {
        $request->validate([
            'name' => ['required'],
            'family' => ['required'],
            'phone' => ['required','unique:users'],
            'code_mli' => ['required'],
            'password' => ['required'],
        ],[
            'name.required'=>'الزامی است ',
            'family.required'=>'الزامی است ',
            'phone.required'=>'الزامی است ',
            'phone.unique'=>' این شماره موبایل قبلا ثبت شده است  ',
            'code_mli.required'=>'الزامی است ',
            'password.required'=>'الزامی است ',
        ]);
    }

    private function getData(): array
    {
        $data = [
            'name' => request('name'),
            'family' => request('family'),
            'code_mli' => request('code_mli'),
            'phone' => request('phone'),
            'password' => Hash::make(request()->input('password')),
        ];
        return $data;
    }
}
