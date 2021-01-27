<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-01-14
 * Time: 14:47
 */

namespace App\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use JoseChan\UserLogin\Constants\User;

/**
 * Class CardOrder
 * @package App\Models
 * @property Collection $cardOrderChild
 * @property int $id
 * @property int $user_id
 * @property string $order_sn
 * @property int $card_id
 * @property int $expired_time
 */
class CardOrder extends Model
{
    protected $table = "px_card_order";

    protected $fillable = ["user_id", "order_sn", "child_name", "child_tel", "child_birth", "child_gender", "expired_time", "card_id"];

    public static function getUserUsefulCard()
    {
        CardOrder::query()->where("user_id", "=", User::$info['id']);
    }

    public function setAge()
    {
        $now = new Carbon("now");
        $child_birth = new Carbon($this->child_birth);
        $this->setAttribute("age", $now->year - $child_birth->year);

        return $this;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cardOrderChild()
    {
        return $this->hasMany(CardOrderChild::class, "card_order_id", "id");
    }


    /**
     * 获取可用卡
     * @return \Illuminate\Database\Eloquent\Builder[]|Collection
     */
    public static function getUsefulCard()
    {
        $card_order = CardOrder::query()
            ->where("user_id", "=", User::$info['id'])
            ->where("status", "=", 1)
            ->where("expired_time", ">", time())
            ->get();

        return $card_order;
    }

    /**
     * 获取用户当前vip登记
     * @return int|mixed
     */
    public static function getUserVipLevel()
    {
        $card_order = CardOrder::query()
            ->where("user_id", "=", User::$info['id'])
            ->where("status", "=", 1)
            ->where("expired_time", ">", time())
            ->get();

        if ($card_order->isEmpty()) {
            return 0;
        }

        return $card_order->max("card_id");
    }

    /**
     * @param Collection $card_order
     * @return int
     */
    public static function getUserVipLevelByCardOrder($card_order)
    {
        if ($card_order->isEmpty()) {
            return 0;
        }

        return $card_order->max("card_id");
    }

    /**
     * @param Collection $card_order
     * @return int
     */
    public static function getVipExpiredByCardOrder($card_order)
    {
        if ($card_order->isEmpty()) {
            return 0;
        }

        return $card_order->max("expired_time");
    }

    /**
     * 根据用户ID获取用户vip等级
     * @param $user_id
     * @return int|mixed
     */
    public static function getUserVipLevelByUserId($user_id)
    {
        $card_order = CardOrder::query()
            ->where("user_id", "=", $user_id)
            ->where("status", "=", 1)
            ->where("expired_time", ">", time())
            ->get();

        if ($card_order->isEmpty()) {
            return 0;
        }

        return $card_order->max("card_id");
    }

}