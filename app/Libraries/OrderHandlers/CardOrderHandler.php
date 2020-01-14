<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-01-05
 * Time: 10:12
 */

namespace App\Libraries\OrderHandler;


use App\Models\CardOrder;
use App\Models\Child;
use App\Models\Order as OrderModel;
use JoseChan\UserLogin\Constants\User;

class CardOrderHandler extends AbstractOrderHandler
{

    public function validate($order_data): bool
    {
        return $this->validator($order_data,[
            "child_id" => "required|Integer"
        ]);
    }

    /**
     * @param OrderModel $order
     * @param $order_date
     * @return CardOrder|mixed
     * @throws \Exception
     */
    public function create(OrderModel $order, $order_date)
    {
        //获取常用人信息
        $child_id = $order_date['child_id'];

        /** @var Child $child */
        $child = Child::find($child_id);

        if(!$child){
            throw new \Exception("常用人不存在");
        }

        $data = [
            "user_id" => User::$info['id'],
            "order_sn" => $order->order_sn,
            "child_name" => $child->name,
            "child_tel" => $child->tel,
            "child_birth" => $child->birth,
            "child_gender" => $child->gender,
        ];

        $card_order = new CardOrder($data);

        if(!$card_order->save()){
            throw new \Exception("生成业务单失败");
        }

        return $card_order;
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