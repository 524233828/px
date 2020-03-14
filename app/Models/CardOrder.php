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

    public static function getUserVipLevel()
    {
        $card_order = CardOrder::query()
            ->where("user_id", "=", User::$info['id'])
            ->where("status", "=", 1)
            ->where("expired_time", ">", time())
            ->get();

        if($card_order->isEmpty()){
            return 0;
        }

        return $card_order->max("card_id");

    }


}