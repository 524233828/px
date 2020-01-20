<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-01-05
 * Time: 18:01
 */

namespace App\Models;


use App\Collections\ClassCollection;
use App\Collections\ShopCollection;
use Encore\Admin\Auth\Database\Administrator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * 店铺模型
 * Class Shop
 * @package App\Models
 * @property integer $id ID
 * @property integer $admin_id 商户ID
 * @property string $name 名称
 * @property string $desc 描述
 * @property string $created_at 创建时间
 * @property string $updated_at 更新时间
 * @property string $headimg_url 头图
 * @property integer $status 状态
 * @property string $latitude 纬度
 * @property string $longitude 经度
 * @property string $position 详细地址
 * @property integer $province_id 省
 * @property integer $city_id 市
 * @property integer $district_id 区
 * @property integer $comment_star 分数
 * @property integer $comment_count 评价数
 * @property Collection $comments 评价列表
 * @property ClassCollection $classes 课程列表
 * @property Collection $video 视频列表
 */
class Shop extends Model
{
    use Image;

    protected $table = "px_shop";

    public const SORT_NOT = 0;
    public const SORT_SYNTHETIC = 1;
    public const SORT_STAR = 2;
    public const SORT_DISTANCE = 3;

    public static $sort_type = [
        self::SORT_NOT,
        self::SORT_SYNTHETIC,
        self::SORT_STAR,
        self::SORT_DISTANCE,
    ];

    /**
     * @var int $distance 距离单位m
     */
    private $distance = 0;

    public function business()
    {
        return $this->belongsTo(Administrator::class, "admin_id", "id");
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, "shop_id", "id");
    }

    public function classes()
    {
        return $this->hasMany(Classes::class, "shop_id", "id");
    }

    public function video()
    {
        return $this->hasMany(Video::class, "business_id", "id")
            ->where("type", "=", Video::TYPE_SHOP);
    }


    public function getHeadimgUrlAttribute($value)
    {
        return $this->imageHandle($value);
    }

    /**
     * 获取距离
     * @param $value
     * @return string
     */
    public function getDistanceAttribute($value)
    {
        if ($value > 100000) {
            $value = "≥100km";
        } elseif ($value > 1000) {
            $value = bcdiv($value, 1000, 2) . "km";
        } else {
            $value .= "m";
        }
        return $value;
    }

    public static function getSelector($where = [])
    {
        $shops = self::query()->where($where)->get(["id", "name"]);

        $shop_list = [0 => "请选择"];
        foreach ($shops as $shop) {
            $shop_list[$shop['id']] = $shop['name'];
        }

        return $shop_list;
    }

    public function newCollection(array $models = [])
    {
        return new ShopCollection($models); // TODO: Change the autogenerated stub
    }

    /**
     * 计算某点到店铺的距离
     * @param $latitude
     * @param $longitude
     * @return float
     */
    public function computeDistance($latitude, $longitude)
    {
        $this->distance = round(
            6378.138 * 2 *
            asin(
                sqrt(
                    pow(
                        sin(
                            ($this->latitude * pi() / 180 - $latitude * pi() / 180) / 2
                        ), 2
                    ) +
                    cos(
                        $this->latitude * PI() / 180
                    ) *
                    cos(
                        $this->latitude * PI() / 180
                    ) *
                    pow(
                        sin(
                            ($this->longitude * pi() / 180 - $longitude * pi() / 180) / 2
                        ),
                        2)
                )
            ) * 1000
        );

        $this->setAttribute("distance", $this->distance);

        return $this->distance;
    }

    /**
     * 计算评价信息
     */
    public function computeCommentsInfo()
    {
        $this->setAttribute("comment_count", $this->comments->count());
        $this->setAttribute("comment_star", $this->comments->avg("star"));
        return $this;
    }
}