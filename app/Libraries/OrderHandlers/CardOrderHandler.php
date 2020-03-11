<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-01-05
 * Time: 10:12
 */

namespace App\Libraries\OrderHandler;


use App\Models\Card;
use App\Models\CardOrder;
use App\Models\CardOrderChild;
use App\Models\Child;
use App\Models\Config;
use App\Models\Order as OrderModel;
use Illuminate\Database\Eloquent\Collection;
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
        return $this->validator($order_data, [
            "child_id" => "required",
            "card_id" => "required|Integer"
        ]);
    }

    /**
     * 创建业务相关记录
     * @param OrderModel $order
     * @param $order_data
     * @return CardOrder|mixed
     * @throws \Exception
     */
    public function create(OrderModel $order, $order_data)
    {
        //获取卡的信息
        $card_id = $order_data['card_id'];
        /** @var Card $card */
        $card = Card::query()->find($card_id);
        if (!$card) {
            throw new \Exception("卡券不存在");
        }

        //获取常用人信息
        $child_ids = $order_data['child_id'];
        $child_ids = explode(",", $child_ids);

        if(count($child_ids) > $card->use_member){
            throw new \Exception("选择人数超过可选数量");
        }

        /** @var Collection $child */
        $child = Child::query()->whereIn("id", $child_ids)->get();

        if ($child->isEmpty()) {
            throw new \Exception("常用人不存在");
        }

        $data = [
            "user_id" => User::$info['id'],
            "order_sn" => $order->order_sn,
            "child_name" => $child->first()->name,
            "child_tel" => $child->first()->tel,
            "child_birth" => $child->first()->birth,
            "child_gender" => $child->first()->gender,
            "expired_time" => time() + $card->expired_time,
            "card_id" => $card->id
        ];

        $child_data = [];
        $child->map(function (Child $item) use (&$child_data) {
            $child_data[] = [
                "child_name" => $item->name,
                "child_tel" => $item->tel,
                "child_birth" => $item->birth,
                "child_gender" => $item->gender,
            ];
        });


        $models = CardOrderChild::buildCardOrderChild($child_data);

        $card_order = new CardOrder($data);

        if (!$card_order->save() || !$card_order->cardOrderChild()->saveMany($models)) {
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
        /** @var CardOrder|null $card_order */
        $card_order = CardOrder::query()->where("order_sn", "=", $order->order_sn)->first();

        if (!$card_order) {
            return false;
        }

        /** @var Card $card */
        $card = Card::query()->find($card_order->card_id);

        $card_order->status = 1;
        $card_order->expired_time = time() + $card->expired_time;

        if ($card_order->save()) {
            return true;
        }

        return false;
    }

    /**
     * 获取价格
     * @param $order_data
     * @return float
     * @throws
     */
    public function getMoney($order_data): float
    {
        /** @var Card $card */
        $card = Card::query()->find($order_data['card_id']);

        if (!$card) {
            throw new \Exception("卡券不存在");
        }

        return $card->amount;
    }

    /**
     * @param $order_data
     * @return string
     * @throws \Exception
     */
    public function getInfo($order_data): string
    {
        /** @var Card $card */
        $card = Card::query()->find($order_data['card_id']);

        if (!$card) {
            throw new \Exception("卡券不存在");
        }

        return $card->name;
    }

    /**
     * @param $order_data
     * @return string
     * @throws \Exception
     */
    public function getImage($order_data): string
    {
        /** @var Card $card */
        $card = Card::query()->find($order_data['card_id']);

        if (!$card) {
            throw new \Exception("卡券不存在");
        }

        return $card->image_url;
    }
}