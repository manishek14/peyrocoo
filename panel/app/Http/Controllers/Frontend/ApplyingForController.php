<?php

namespace App\Http\Controllers\Frontend;

use App\Constants\Constant;
use App\Http\Controllers\Frontend\BaseController;
use App\Http\Controllers\Controller;
use App\Models\ApplyingFor;
use Illuminate\Http\Request;

class ApplyingForController extends BaseController
{


    public function index()
    {
        $user = auth()->user();
        $data = ApplyingFor::where('user_id', $user->id)->paginate();
        return view('app.applying-for.all',compact('data'));
    }
    public function answer(ApplyingFor $applyingFor)
    {
        $data = $applyingFor;
        return view('app.applying-for.answer',compact('data'));
    }

    public function create()
    {
        return view('app.applying-for.create');
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        // validate request
        $this->validateForm();
        // prepare data to insert in db
        $data = $this->getData($request, $user);
        // create new plan
        $applyingFor = ApplyingFor::create($data);

        // redirect
        if ($applyingFor instanceof ApplyingFor) {
            return redirect()->route('app.create-applying.all')->with('success', 'ثبت درخواست شما با موفقیت انجام شد');
        }
        return redirect()->back();
    }

    private function validateForm()
    {
        request()->validate([
            'title' => ['required'],
            'file' => ['required'],
        ], [
            'title.required' => 'عنوان را وارد کنید ',
            'file.required' => "عکس راوارد کنید",
        ]);
    }

    private function getData($request, $user): array
    {
        $data = [
            'title' => request('title'),
            'full_name' => $user->name . ' ' . $user->family,
            'code_mli' => $user->code_mli,
            'phone' => $user->phone,
            'user_id' => $user->id,
        ];

        if ($request->hasFile('file')) {
            $data['image'] = $this->uploadFile($request->file('file'), Constant::REQUEST_IMAGE_PATH);
        }
        return $data;
    }

}
