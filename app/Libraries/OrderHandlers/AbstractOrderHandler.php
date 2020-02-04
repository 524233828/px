<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-01-05
 * Time: 10:15
 */

namespace App\Libraries\OrderHandler;

use App\Models\Order as OrderModel;

/**
 * 业务订单逻辑处理器
 * Class AbstractOrderHandler
 * @package App\Libraries\OrderHandler
 */
abstract class AbstractOrderHandler
{

    /**
     * 校验业务相关参数
     * @param $order_data
     * @return bool
     */
    abstract public function validate($order_data): bool;

    /**
     * 创建业务订单，返回一个业务订单对象
     * @param OrderModel $order
     * @param $order_data
     * @return mixed
     */
    abstract public function create(OrderModel $order, $order_data);

    /**
     * 支付成功回调处理
     * @param OrderModel $order
     * @return mixed
     */
    abstract public function buySuccess(OrderModel $order);

    /**
     * 获取业务金额
     * @param $order_data
     * @return float
     */
    abstract public function getMoney($order_data): float;

    /**
     * 获取购买信息
     * @param $order_data
     * @return string
     */
    abstract public function getInfo($order_data): string;

    /**
     * 获取购买信息
     * @param $order_data
     * @return string
     */
    abstract public function getImage($order_data): string;

    /**
     * 参数检查
     * @param $data
     * @param $rule
     * @return bool
     */
    protected function validator($data, $rule)
    {
        $validator = validator($data, $rule);

        return !$validator->fails();
    }

}