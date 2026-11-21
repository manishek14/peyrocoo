<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Constant;
use App\Filters\UsersFilter;
use App\Models\Category;
use App\Models\SocialNetwork;
use App\Models\SocialNetworkUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UsersController extends BaseController
{
    public function index()
    {
        $users = User::filter(new UsersFilter())->paginate();
        return view('admin.users.all', compact('users'));
    }



}
