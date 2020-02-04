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

}