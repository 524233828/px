<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-01-20
 * Time: 11:48
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * Class Video
 * @package App\Models
 * @property integer $id ID
 * @property integer $type ID
 * @property string $path ID
 * @property integer $business_id ID
 * @property string $created_at ID
 * @property string $updated_at ID
 */
class Video extends Model
{
    use Image;
    protected $table = "px_video";

    public const TYPE_SHOP = 1;
    public const TYPE_CLASS = 2;

    public static $business_type = [
        self::TYPE_SHOP => "店铺",
        self::TYPE_CLASS => "课程",
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class, "business_id", "id");
    }

    public function classes(){
        return $this->belongsTo(Classes::class, "business_id", "id");
    }

    public static function getBusiness($type)
    {
        if ($type == self::TYPE_SHOP) {
            $business = Shop::query()->get(["id", "name as text"]);
        } else {
            $business = Classes::query()->get(["id", "name as text", "shop_id"]);

            if($business->isNotEmpty()){
                $business->map(function (Classes $classes, $key){
                    $classes->text = $classes->shop->name . "-" . $classes->text;
                });
            }
        }

        return $business;
    }

//    public function getPathAttribute($value)
//    {
//        return $this->imageHandle($value);
//    }
}