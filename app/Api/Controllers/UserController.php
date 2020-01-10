<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-01-09
 * Time: 19:09
 */

namespace App\Api\Controllers;


use App\Models\PxUser;
use JoseChan\Base\Api\Controllers\Controller;
use JoseChan\UserLogin\Constants\User;

class UserController extends Controller
{

    public function info()
    {
        /** @var PxUser $info */
        return $this->response(User::$info->getFrontFields());
    }

    public function code()
    {
    }


}