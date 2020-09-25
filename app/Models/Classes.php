<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-01-04
 * Time: 14:59
 */

namespace App\Models;


use App\Collections\ClassCollection;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Classes
 * @package App\Models
 * @property integer $id ID
 * @property integer $shop_id 所属商店
 * @property string $name 名字
 * @property string $info 简介
 * @property string $desc 描述
 * @property string $pic 图片
 * @property float $price 价格
 * @property float $vip_price 价格
 * @property string $start_time 上课时间
 * @property string $end_time 下课时间
 * @property string $created_at 创建时间
 * @property string $updated_at 更新时间
 * @property integer $category_id 分类ID
 * @property integer $start_age 最小适龄
 * @property integer $end_age 最大适龄
 * @property array $week 上课的星期
 * @property Shop $shop 店铺
 * @property Collection $comments 评价列表
 * @property Collection $schoolTime 上课时间
 * @property Video $video 视频列表
 */
class Classes extends Model
{
    use Image;

    protected $table = "px_class";

    public function shop()
    {
        return $this->belongsTo(Shop::class, "shop_id", "id");
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, "class_id", "id")->select(["star"]);
    }

    public function schoolTime()
    {
        return $this->hasMany(SchoolTime::class, "class_id", "id");
    }

    public function getPicAttribute($value)
    {
        return $this->imageHandle($value);
    }

    public function video()
    {
        return $this->hasOne(Video::class, "business_id", "id")
            ->where("type", "=", Video::TYPE_CLASS);
    }

    public function getVipPriceAttribute()
    {
        $vip_price = $this->attributes['vip_price'];
        if (empty($vip_price)) {
            $rate = Config::get("vip_price_rate", 0);
            $vip_price = $rate * $this->attributes['price'];
        }

        if (empty($vip_price)) {
            $vip_price = $this->attributes['price'];
        }

        return $vip_price;
    }

    public function newCollection(array $models = [])
    {
        return new ClassCollection($models); // TODO: Change the autogenerated stub
    }

    /**
     * 组装评价参数
     * @return $this
     */
    public function computeCommentsInfo()
    {
        $this->setAttribute("comment_count", $this->comments->count());
        $star = $this->comments->avg("star");
        $this->setAttribute("comment_star", $star ? number_format($star, 1) : 4);

        return $this;
    }

    public function setAgeInfo()
    {
        if ($this->start_age == 0 && $this->end_age == 0) {
            $this->setAttribute("age_info", "适合所有人群");
        } else if ($this->start_age == 0) {
            $this->setAttribute("age_info", "适合{$this->end_age}岁以下人群");
        } else if ($this->end_age == 0) {
            $this->setAttribute("age_info", "适合{$this->start_age}岁以上人群");
        } else {
            $this->setAttribute("age_info", "适合{$this->start_age}岁至{$this->end_age}岁人群");
        }

        return $this;
    }

    public function isCollect(PxUser $user)
    {
        $count = Collect::query()->where([
            ["type", "=", Collect::TYPE_CLASS],
            ["business_id", "=", $this->id],
            ["uid", "=", $user->id]
        ])->count();

        $this->setAttribute("is_collect", $count);

        return $this;
    }

    public function isLike(PxUser $user)
    {
        $count = Like::query()->where([
            ["business_id", "=", $this->id],
            ["type", "=", Like::TYPE_CLASS],
            ["uid", "=", $user->id]
        ])->count();

        $this->setAttribute("is_like", $count);

        return $this;
    }

    public function getWeekAttribute($value){
        if(empty($value)){
            return [];
        }

        return json_decode($value, true);
    }

}