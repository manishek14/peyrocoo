<?php

namespace App\Models;

use App\Filters\Contracts\Filterable;
use App\Presenters\Contracts\Presentable;
use App\Presenters\Web\Admin\AdminPresenter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use HasFactory, Filterable, Presentable,SoftDeletes;

    protected $guarded = ['id'];
    protected $webPresenter = AdminPresenter::class;


}
