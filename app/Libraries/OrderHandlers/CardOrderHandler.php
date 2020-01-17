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
use App\Models\Config;
use App\Models\Order as OrderModel;
use JoseChan\UserLogin\Constants\User;

/**
 * 卡券购买业务处理
 * Class CardOrderHandler
 * @package App\Libraries\OrderHandler
 */
class CardOrderHandler extends AbstractOrderHandler
{

    /**
     * 参数过滤
     * @param $order_data
     * @return bool
     */
    public function validate($order_data): bool
    {
        return $this->validator($order_data,[
            "child_id" => "required|Integer"
        ]);
    }

    /**
     * 创建业务相关记录
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

    /**
     * 回调业务处理
     * @param OrderModel $order
     * @return bool
     */
    public function buySuccess(OrderModel $order)
    {
        return false;
    }

    /**
     * 获取价格
     * @param $order_data
     * @return float
     */
    public function getMoney($order_data): float
    {
        return Config::get("card_amount", 100.00);
    }
}