<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * Class Goods
 * @package App\Models
 * @property int $id ID
 * @property string $name ID
 * @property string $img_url ID
 * @property float $price ID
 * @property float $vip_price ID
 * @property string $created_at ID
 * @property string $updated_at ID
 * @property string $intro ID
 * @property string $desc ID
 * @property integer $num ID
 */
class Goods extends Model
{
    use Image;
    protected $table = "px_goods";

    public function getImgUrlAttribute($value)
    {
        return $this->imageHandle($value);
    }

    public function computeBuyNum()
    {
        $num = GoodsOrder::query()->where([
            ["goods_id", "=", $this->id],
            ["status", "=", 1],
        ])->count();

        $this->setAttribute("buy_num", $num);

        return $this;

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

    public function removeVipPrice()
    {
        unset($this->attributes['vip_price']);

        return $this;
    }

}