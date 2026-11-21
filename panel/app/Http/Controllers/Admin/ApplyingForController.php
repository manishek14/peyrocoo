<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Constant;
use App\Http\Controllers\Frontend\BaseController;
use App\Http\Controllers\Controller;
use App\Models\ApplyingFor;
use Illuminate\Http\Request;

class ApplyingForController extends BaseController
{
    public function index()
    {
        $data = ApplyingFor::orderBy('id', 'DESC')->paginate();
       
        return view('admin.applying-for.all', compact('data'));
    }

    public function answer(ApplyingFor $applyingFor)
    {
        return view('admin.applying-for.answer', compact('applyingFor'));
    }

    public function store(Request $request, ApplyingFor $applyingFor)
    {
      
        $this->validateForm();
        // prepare data to insert in db
        $data = $this->getData($request);
        // create new plan
        $applyingFor = $applyingFor->update($data);
        // redirect
        return redirect()->route('admin.create-applying.all')->with('success', 'عملیات شما با موفقیت ثبت شد ');
    }

    private function validateForm()
    {
        request()->validate([
            'description' => ['required'],
            'javab' => ['required'],
        ], [
            'description.required' => 'توضیحات  را وارد کنید ',
            'javab.required' => "عکس راوارد کنید",
        ]);
    }

    private function getData($request): array
    {
        $data = [
            'description' => request('description')
        ];

        if ($request->hasFile('javab')) {
            $data['javab'] = $this->uploadFile($request->file('javab'), Constant::REQUEST_IMAGE_PATH);
        }
        return $data;
    }
}
