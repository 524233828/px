<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-01-22
 * Time: 20:02
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use JoseChan\UserLogin\Constants\User;

class ClassOrder extends Model
{

    protected $table = "px_class_order";

    protected $fillable = ["user_id", "order_sn", "class_id", "admin_id"];

    public static function checkUserIsBuy($class_id)
    {
        $classOrder = self::query()
            ->where("user_id", "=", User::$info['id'])
            ->where("status", "=", 1)
            ->where("class_id", "=", $class_id)
            ->get();

        return $classOrder->isNotEmpty();
    }
}