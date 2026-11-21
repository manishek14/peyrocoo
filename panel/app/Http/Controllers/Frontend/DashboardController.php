<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('app.dashboard');
    }
    
    public function rahnama()
    {
         return view('app.rahnama');
    }
}
