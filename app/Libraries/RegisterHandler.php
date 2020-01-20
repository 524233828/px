<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-01-04
 * Time: 15:09
 */

namespace App\Libraries;


use App\Models\PxUser;
use App\Models\Wallet;
use JoseChan\UserLogin\Libraries\Wechat\Miniprogram\RegisterHandler\AbstractHandler;

/**
 * 注册处理类
 * Class RegisterHandler
 * @package App\Libraries
 */
class RegisterHandler extends AbstractHandler
{

    public function handler(array $user_info)
    {
        $user = new PxUser();

        $user->open_id = $user_info['openid'];

        $user->getConnection()->beginTransaction();

        if($user->save()){
            //创建用户钱包
            $wallet = new Wallet([
                "uid" => $user->id,
                "amount" => 0,
                "freeze_amount" => 0
            ]);

            if($wallet->save()){
                $user->getConnection()->commit();
                return $user;
            }
        }

        $user->getConnection()->rollBack();
        return false;

    }

}