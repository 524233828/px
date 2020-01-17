<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-01-05
 * Time: 10:15
 */

namespace App\Libraries\OrderHandler;

use App\Models\Order as OrderModel;

abstract class AbstractOrderHandler
{

    abstract public function validate($order_data): bool;

    /**
     * 创建业务订单，返回一个业务订单对象
     * @param OrderModel $order
     * @param $order_date
     * @return mixed
     */
    abstract public function create(OrderModel $order, $order_date);

    abstract public function buySuccess(OrderModel $order);

    abstract public function getMoney($order_data): float;

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