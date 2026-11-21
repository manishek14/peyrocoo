<?php


namespace App\Filters;
use App\Filters\Contracts\QueryFilter;

class UsersFilter extends QueryFilter
{
    public function  family ($value = null)
    {
        if(!is_null($value)){
            return $this->builder->where('family', 'like', '%'.$value.'%');
        }
        return $this->builder;
    }
    public function phone($value = null)
    {
        if(!is_null($value)){
            return $this->builder->where('phone', 'like', '%'.$value.'%');
        }
        return $this->builder;
    }


}
