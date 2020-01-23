<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-01-05
 * Time: 10:10
 */

namespace App\Libraries\OrderHandler;

use App\Libraries\OrderHandlers\ClassOrderHandler;
use JoseChan\UserLogin\Constants\User;
use App\Models\Order as OrderModel;

/**
 * 订单处理
 * Class Order
 * @package App\Libraries\OrderHandler
 */
class Order
{
    /**
     * 订单类型 - 卡券
     */
    public const CARD = 0;

    /**
     * 订单类型 - 课程
     */
    public const CLASSES = 1;

    /**
     * 订单类型 - 商品
     */
    public const GOODS = 2;

    /**
     * 业务处理类
     * @var array $gateway
     */
    private static $gateway = [
        self::CARD => CardOrderHandler::class,
        self::CLASSES => ClassOrderHandler::class
    ];

    /**
     * 创建订单
     * @param int $order_type
     * @param array $order_data
     * @return OrderModel
     * @throws \Exception
     */
    public static function create($order_type = self::CARD, $order_data = []): OrderModel
    {
        if (!isset(self::$gateway[$order_type])) {
            throw new \Exception("order type not found");
        }

        /** @var AbstractOrderHandler $handler */
        $handler = new self::$gateway[$order_type]();

        //校验相关业务参数
        if (!$handler->validate($order_data)) {
            throw new \Exception("params error");
        }

        //获取该业务的价格
        $money = $handler->getMoney($order_data);

        //购买的用户ID
        $uid = User::$info['id'];

        //组装订单参数
        $data = [
            "order_sn" => OrderModel::getOrderSn(),
            "uid" => $uid,
            "type" => $order_type,
            "money" => $money,
            "info" => $handler->getInfo($order_data),
        ];

        //生成订单
        $order = new OrderModel($data);
        $order->getConnection()->beginTransaction();

        if ($order->save()) {

            try{
                $business_order = $handler->create($order, $order_data);
                $order->getConnection()->commit();
                return $order;
            }catch (\Exception $exception){
                $order->getConnection()->rollBack();
                throw $exception;
            }
        }

        throw new \Exception("create order fails");

    }

    /**
     * 支付成功处理
     * @param OrderModel $order
     * @return bool
     * @throws \Exception
     */
    public static function buySuccess(OrderModel $order)
    {
        //处理订单状态
        $order->getConnection()->beginTransaction();
        if (self::$gateway[$order->type]) {
            $order->status = 1;
            //处理订单状态成功，处理相关业务的数据
            if ($order->save()) {
                /** @var AbstractOrderHandler $handler */
                $handler = new self::$gateway[$order->type]();

                if ($handler->buySuccess($order)) {
                    $order->getConnection()->commit();
                    return true;
                } else {
                    $order->getConnection()->rollBack();
                    return false;
                }
            }
        }

        return false;
    }
}