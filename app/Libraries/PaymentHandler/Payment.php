<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-01-04
 * Time: 16:14
 */

namespace App\Libraries\PaymentHandler;


use App\Models\Order;
use JoseChan\UserLogin\Constants\User;
use Runner\NezhaCashier\Cashier;
use Runner\NezhaCashier\Utils\Amount;
use App\Libraries\OrderHandler\Order as OrderHandler;

class Payment
{
    const WECHAT_MINIPROGRAM = "wechat_mina";

    public static function unified(Order $order, $pay_type = self::WECHAT_MINIPROGRAM)
    {
        $order_info = [
            "order_id" => $order->order_sn,
            "amount" => Amount::dollarToCent($order->total_fee),
            "subject" => $order->info,
            'currency' => 'CNY',
            'description' => $order['info'],
//            'return_url' => $redirect_url,
        ];

        if(!empty($redirect_url)){
            $order_info['return_url'] = $redirect_url;
        }

        $order_info['user_ip'] = client_ip(0, true);


        if(substr($pay_type,0, 6) == "wechat"){
            if(!empty($code)){
                $order_info['extras']['code'] = $code;
            }

            if(isset($user['openid'])){
                $order_info['extras']['open_id'] = User::$info['openid'];
            }
        }

        $config = config("payment");
        $pay = new Cashier($pay_type, $config[$pay_type]);

        if(substr($pay_type,0, 6) == "wechat"){
            $params = $pay->charge($order_info)->get("parameters");
            $params['timeStamp'] = (string)$params['timeStamp'];
            return ["jsApiParameters" => json_encode($params, JSON_UNESCAPED_UNICODE)];
        }else{
            return $pay->charge($order_info)->get("charge_url");
        }
    }

    public static function notify($pay_sn)
    {
        /** @var Order|null $order */
        $order = Order::query()->where("pay_sn", "=", $pay_sn)->first();

        if($order){
            $result = OrderHandler::buySuccess($order);
        }

    }
}