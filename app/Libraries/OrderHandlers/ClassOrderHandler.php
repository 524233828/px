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
use App\Models\ClassOrder;
use App\Models\Order as OrderModel;
use App\Models\PxUser;
use JoseChan\UserLogin\Constants\User;

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

        /** @var PxUser $user */
        $user = User::$info;

        $class_order = new ClassOrder([
            "user_id" => $user->id,
            "order_sn" => $order->order_sn,
            "class_id" => $class_id,
        ]);

        if(!$class_order->save()){
            throw new \Exception("生成业务单失败");
        }

        return $class_order;
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