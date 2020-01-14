<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-01-14
 * Time: 16:13
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Appoint extends Model
{

    protected $table = "px_appoint";

    protected $fillable = ["shop_id", "uid", "class_id", "status", "card_id", "admin_id"];

    public static function countShopCardAppointNum($shop_id)
    {
        return self::query()->where("shop_id", "=", $shop_id)->groupBy(["card_id"])->count();
    }

    public static function countBusinessCardAppointNum($admin_id)
    {
        return self::query()->where("admin_id", "=", $admin_id)->count();
    }
}