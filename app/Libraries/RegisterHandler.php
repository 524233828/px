<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-01-04
 * Time: 15:09
 */

namespace App\Libraries;


use App\Models\PxUser;
use JoseChan\UserLogin\Libraries\Wechat\Miniprogram\RegisterHandler\AbstractHandler;

class RegisterHandler extends AbstractHandler
{

    public function handler(array $user_info)
    {
        $user = new PxUser();

        $user->openid = $user_info['openid'];

        if($user->save()){
            return $user;
        }

        return false;

    }

}