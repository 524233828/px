<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-02-04
 * Time: 11:39
 */

namespace App\Libraries\OrderHandlers;


use App\Libraries\OrderHandler\AbstractOrderHandler;
use App\Models\CardOrder;
use App\Models\Goods;
use App\Models\GoodsOrder;
use App\Models\Order as OrderModel;
use App\Models\PxUser;
use JoseChan\UserLogin\Constants\User;

class GoodsOrderHandler extends AbstractOrderHandler
{

    public function validate($order_data): bool
    {
        return $this->validator($order_data,[
            "goods_id" => "required|Integer"
        ]);
    }

    public function create(OrderModel $order, $order_data)
    {
        $goods_id = $order_data['goods_id'];

        /** @var Goods|null $class */
        $goods = Goods::query()->find($goods_id);

        if(!$goods){
            throw new \Exception("商品不存在");
        }

        /** @var PxUser $user */
        $user = User::$info;

        $class_order = new GoodsOrder([
            "user_id" => $user->id,
            "order_sn" => $order->order_sn,
            "goods_id" => $goods_id,
        ]);

        if(!$class_order->save()){
            throw new \Exception("生成业务单失败");
        }

        return $class_order;
    }

    public function buySuccess(OrderModel $order)
    {
        /** @var GoodsOrder|null $goods_order */
        $goods_order = GoodsOrder::query()->where("order_sn", "=", $order->order_sn)->first();

        if(!$goods_order){
            return false;
        }

        $goods_order->status = 1;

        if($goods_order->save()){
            return true;
        }

        return false;
    }

    public function getMoney($order_data): float
    {
        $goods_id = $order_data['goods_id'];

        /** @var Goods|null $goods */
        $goods = Goods::query()->find($goods_id);

        if(!$goods){
            throw new \Exception("商品不存在");
        }

        $user_level = CardOrder::getUserVipLevel();

        if($user_level > 0){
            return $goods->vip_price;
        }

        return $goods->price;

    }

    public function getInfo($order_data): string
    {
        $goods_id = $order_data['goods_id'];

        /** @var Goods|null $goods */
        $goods = Goods::query()->find($goods_id);

        if(!$goods){
            throw new \Exception("商品不存在");
        }

        return "购买【{$goods->name}】";
    }

    public function getImage($order_data): string
    {
        $goods_id = $order_data['goods_id'];

        /** @var Goods|null $goods */
        $goods = Goods::query()->find($goods_id);

        if(!$goods){
            throw new \Exception("商品不存在");
        }

        return $goods->img_url;
    }
}