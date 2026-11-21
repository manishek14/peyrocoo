<?php


namespace App\Presenters\Web\ApplyingFor;


use App\Constants\Constant;
use App\Presenters\Contracts\Presenter;
use App\Helpers\Format\Date;
use Morilog\Jalali\Jalalian;


class ApplyingForPresenter extends Presenter
{
    public function image()
    {
        if (is_null($this->entity->image) || $this->entity->image == "") {
            return asset('admin-assets/media/avatars/blank.png');
        }
        return str_replace('\\', '/', asset(Constant::REQUEST_IMAGE_PATH . DIRECTORY_SEPARATOR . $this->entity->image));
    }
    public function javab()
    {
        if (is_null($this->entity->javab) || $this->entity->javab == "") {
            return asset('admin-assets/media/avatars/blank.png');
        }
        return str_replace('\\', '/', asset(Constant::REQUEST_IMAGE_PATH . DIRECTORY_SEPARATOR . $this->entity->javab));
    }
    public function date()
    {
    
        return \Morilog\Jalali\CalendarUtils::strftime('Y-m-d H:i:s', strtotime($this->entity->created_at));
    }

 



}
