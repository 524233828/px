<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-01-14
 * Time: 14:47
 */

namespace App\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use JoseChan\UserLogin\Constants\User;

class CardOrder extends Model
{
    protected $table = "px_card_order";

    protected $fillable = ["user_id", "order_sn", "child_name", "child_tel", "child_birth", "child_gender"];

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
}