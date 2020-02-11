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

/**
 * 支付处理类
 * Class Payment
 * @package App\Libraries\PaymentHandler
 */
class Payment
{
    const WECHAT_MINIPROGRAM = "wechat_mina";

    /**
     * 下单
     * @param Order $order
     * @param string $pay_type
     * @return array|bool|mixed|null
     */
    public static function unified(Order $order, $pay_type = self::WECHAT_MINIPROGRAM)
    {
        $pay_sn = Order::getPaySn();

        //更新订单的支付号
        $order->pay_sn = $pay_sn;

        if (!$order->save()) {
            return false;
        }

        $order_info = [
            "order_id" => $pay_sn,
            "amount" => Amount::dollarToCent($order->money),
            "subject" => $order->info,
            'currency' => 'CNY',
            'description' => $order['info'],
//            'return_url' => $redirect_url,
        ];

        if (!empty($redirect_url)) {
            $order_info['return_url'] = $redirect_url;
        }

        $order_info['user_ip'] = client_ip(0, true);

        if (substr($pay_type, 0, 6) == "wechat") {
            if (!empty($code)) {
                $order_info['extras']['code'] = $code;
            }

            if (isset(User::$info['open_id'])) {
                $order_info['extras']['open_id'] = User::$info['open_id'];
            }
        }

        //获取支付对象
        $config = config("payment");
        $pay = new Cashier($pay_type, $config[$pay_type]);

        //向第三方支付系统下单
        if (substr($pay_type, 0, 6) == "wechat") {
            $params = $pay->charge($order_info)->get("parameters");
            $params['timeStamp'] = (string)$params['timeStamp'];
            return ["jsApiParameters" => json_encode($params, JSON_UNESCAPED_UNICODE)];
        } else {
            return $pay->charge($order_info)->get("charge_url");
        }
    }

    /**
     * 订单回调
     * @param $pay_sn
     * @return bool
     * @throws \Exception
     */
    public static function notify($pay_sn)
    {
        $log = myLog("payment_notify");
        /** @var Order|null $order */
        $order = Order::query()->where("pay_sn", "=", $pay_sn)->first();

        $log->debug("order:".json_encode($order->toArray()));

        if ($order) {
            //分销处理
            try{
                Distribution::handler($order);
            }catch (\Exception $exception){
                $log->debug($exception->getMessage() . "\n". $exception->getTraceAsString());
            }

            return OrderHandler::buySuccess($order);
        }

        return false;

    }
}