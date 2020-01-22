<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-01-22
 * Time: 17:08
 */

namespace App\Libraries\OrderHandlers;


use App\Libraries\OrderHandler\AbstractOrderHandler;
use App\Models\Classes;
use App\Models\Order as OrderModel;

class ClassOrderHandler extends AbstractOrderHandler
{

    public function validate($order_data): bool
    {
        return $this->validator($order_data,[
            "class_id" => "required|Integer"
        ]);
    }

    public function create(OrderModel $order, $order_data)
    {
        $class_id = $order_data['class_id'];

        /** @var Classes|null $class */
        $class = Classes::query()->find($class_id);

        if(!$class){
            throw new \Exception("课程不存在");
        }

    }

    public function buySuccess(OrderModel $order)
    {
        // TODO: Implement buySuccess() method.
    }

    public function getMoney($order_data): float
    {
        $class_id = $order_data['class_id'];

        /** @var Classes|null $class */
        $class = Classes::query()->find($class_id);

        if(!$class){
            throw new \Exception("课程不存在");
        }

        return $class->price;
    }
}