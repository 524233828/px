<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-01-05
 * Time: 10:10
 */

namespace App\Libraries\OrderHandler;

use JoseChan\UserLogin\Constants\User;
use App\Models\Order as OrderModel;

class Order
{
    public const CARD = 0;
    public const CLASSES = 1;
    public const GOODS = 2;

    private static $gateway = [
        self::CARD => CardOrderHandler::class
    ];

    public static function create($order_type = self::CARD, $order_data = []): \App\Models\Order
    {
        if (!isset(self::$gateway[$order_type])) {
            throw new \Exception("order type not found");
        }

        /** @var AbstractOrderHandler $handler */
        $handler = new self::$gateway[$order_type]();

        if (!$handler->validate($order_data)) {
            throw new \Exception("params error");
        }

        $money = $handler->getMoney($order_data);

        $uid = User::$info['id'];

        $data = [
            "order_sn" => OrderModel::getOrderSn(),
            "uid" => $uid,
            "type" => $order_type,
            "money" => $money,
        ];

        $order = new OrderModel($data);
        $order->getConnection()->beginTransaction();

        if($order->save()){

            if($order = $handler->create($order, $order_data)){
                $order->getConnection()->commit();
                return $order;
            }else{
                $order->getConnection()->rollBack();
                return null;
            }
        }

        return null;

    }

    public static function buySuccess(OrderModel $order)
    {
        if(self::$gateway[$order->type]){
            /** @var AbstractOrderHandler $handler */
            $handler = new self::$gateway[$order->type]();

            $handler->buySuccess($order);
        }
    }
}