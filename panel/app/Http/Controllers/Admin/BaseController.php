<?php

namespace App\Http\Controllers\Admin;


use App\Constants\Constant;
use App\Helpers\Hash\HashGenerator;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class BaseController extends Controller
{

    protected function uploadFile($uploadedFile, $uploadPath)
    {

      
        $uploaded_file_name = $uploadedFile->getClientOriginalName();
        $fileNameToStore = HashGenerator::make(10) . time() . '_' . $uploaded_file_name;

        // check dir exist
        if(!File::exists($uploadPath)){
            File::makeDirectory($uploadPath,0777,true,true);
        }

        $fileUploaded = $uploadedFile->move(public_path() .'/' . $uploadPath, $fileNameToStore);
        if ($fileUploaded) {
            return $fileNameToStore;
        } else {
            return null;
        }
    }

}
