<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-01-14
 * Time: 16:13
 */

namespace App\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * 预约模型
 * Class Appoint
 * @package App\Models
 * @property string $id ID
 * @property integer $shop_id ID
 * @property integer $uid ID
 * @property integer $class_id ID
 * @property integer $status ID
 * @property string $created_at ID
 * @property string $updated_at ID
 * @property integer $card_id ID
 * @property integer $admin_id ID
 * @property string $start_time ID
 * @property string $end_time ID
 * @property Classes $classes ID
 * @property Shop $shop ID
 */
class Appoint extends Model
{

    protected $table = "px_appoint";

    protected $fillable = ["shop_id", "uid", "class_id", "status", "card_id","card_child_id", "admin_id", "appoint_sn", "start_time", "end_time"];

    public static $weekLang = ["周日", "周一", "周二", "周三", "周四", "周五", "周六"];

    public function classes()
    {
        return $this->belongsTo(Classes::class, "class_id", "id");
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class, "shop_id", "id");
    }

    public function user()
    {
        return $this->belongsTo(PxUser::class, "uid", "id");
    }

    /**
     * 统计卡券在某商店的预约次数
     * @param $shop_id
     * @return int
     */
    public static function countShopCardAppointNum($shop_id)
    {
        return self::query()->where("shop_id", "=", $shop_id)->groupBy(["card_id"])->count();
    }

    /**
     * 统计某卡券在某商户下预约次数
     * @param $admin_id
     * @param $card_child_id
     * @return int
     */
    public static function countBusinessCardAppointNum($admin_id, $card_child_id)
    {
        return self::query()->where([
            ["admin_id", "=", $admin_id],
            ["card_child_id", "=", $card_child_id],
        ])->count();
    }

    public static function getAppointSn()
    {
        return (string)(microtime(true) * 10000);
    }

    public function getStartTimeAttribute($value){
        $datetime = new Carbon($value);
        return $datetime->format("m月d日 H时i分"). " " . static::$weekLang[$datetime->dayOfWeek];
    }
}