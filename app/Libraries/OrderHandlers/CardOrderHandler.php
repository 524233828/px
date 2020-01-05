<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-01-05
 * Time: 10:12
 */

namespace App\Libraries\OrderHandler;


use App\Models\Order as OrderModel;

class CardOrderHandler extends AbstractOrderHandler
{

    public function validate($order_data): bool
    {
        return $this->validator($order_data,[]);
    }

    public function create(OrderModel $order, $order_date): OrderModel
    {
        return false;
    }

    public function buySuccess(OrderModel $order)
    {
        return false;
    }

    public function getMoney($order_data): float
    {
        return 100.00;
    }
}