<?php

namespace App\Models;

use App\Presenters\Contracts\Presentable;
use App\Presenters\Web\ApplyingFor\ApplyingForPresenter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplyingFor extends Model
{
        use HasFactory, Presentable;

    protected $guarded = ['id'];

    protected $webPresenter = ApplyingForPresenter::class;

}
