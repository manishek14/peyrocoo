<?php


namespace App\Presenters\Web\User;


use App\Constants\Constant;
use App\Presenters\Contracts\Presenter;

class UserPresenter extends Presenter
{
    public function avatar()
    {
        if (is_null($this->entity->avatar) || $this->entity->avatar == "") {
            return asset('admin-assets/media/avatars/blank.png');
        }
        return str_replace('\\', '/', asset(Constant::USERS_AVATAR_PATH . DIRECTORY_SEPARATOR . $this->entity->avatar));
    }

    public function status()
    {

        if ($this->entity->status == "active") {
            return " <div class='badge badge-light-success'>فعال</div>";
        } elseif ($this->entity->status == "in-active") {
            return "  <div class='badge badge-light-danger'>غیر فعال</div>";
        }
    }

    public function fullName()
    {
        return $this->entity->name . ' ' . $this->entity->family;
    }

}
