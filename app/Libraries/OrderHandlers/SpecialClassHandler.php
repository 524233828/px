<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-03-14
 * Time: 14:45
 */

namespace App\Libraries\OrderHandlers;


use App\Libraries\OrderHandler\AbstractOrderHandler;
use App\Models\Order as OrderModel;
use App\Models\PxUser;
use App\Models\SpecialClass;
use App\Models\SpecialClassOrder;
use JoseChan\UserLogin\Constants\User;

class SpecialClassHandler extends AbstractOrderHandler
{

    public function validate($order_data): bool
    {
        return $this->validator($order_data,[
            "special_class_id" => "required|Integer"
        ]);
    }

    public function create(OrderModel $order, $order_data)
    {
        $class_id = $order_data['special_class_id'];

        /** @var SpecialClass|null $class */
        $class = SpecialClass::query()->find($class_id);

        if(!$class){
            throw new \Exception("课程不存在");
        }

        /** @var PxUser $user */
        $user = User::$info;

        $class_order = new SpecialClassOrder([
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
        /** @var SpecialClassOrder|null $class_order */
        $class_order = SpecialClassOrder::query()->where("order_sn", "=", $order->order_sn)->first();

        if(!$class_order){
            return false;
        }

        $class_order->status = 1;

        if($class_order->save()){
            return true;
        }

        return false;
    }

    public function getMoney($order_data): float
    {
        $class_id = $order_data['special_class_id'];

        /** @var SpecialClass|null $class */
        $class = SpecialClass::query()->find($class_id);

        if(!$class){
            throw new \Exception("课程不存在");
        }

        return $class->price;
    }

    public function getInfo($order_data): string
    {
        $class_id = $order_data['special_class_id'];

        /** @var SpecialClass|null $class */
        $class = SpecialClass::query()->find($class_id);

        if(!$class){
            throw new \Exception("课程不存在");
        }

        return "购买课程【{$class->name}】";
    }

    public function getImage($order_data): string
    {
        $class_id = $order_data['special_class_id'];

        /** @var SpecialClass|null $class */
        $class = SpecialClass::query()->find($class_id);

        if(!$class){
            throw new \Exception("课程不存在");
        }

        return $class->image_url;
    }
}
