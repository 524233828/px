<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-03-10
 * Time: 08:56
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * Class Card
 * @package App\Models
 * @property string $expired_date
 * @property int $expired_time
 * @property float $amount
 * @property string $name
 * @property string $image_url
 */
class Card extends Model
{

    use Image;

    protected $table = "px_card";

    public function getExpiredDateAttribute()
    {
        if ($this->expired_time < 60) {
            $expired_date = $this->expired_time . "秒";
        } elseif ($this->expired_time < 3600) {
            $expired_date = floor($this->expired_time / 60) . "分";
        } elseif ($this->expired_time < 86400) {
            $expired_date = floor($this->expired_time / 3600) . "小时";
        } elseif ($this->expired_time < 2592000) {
            $expired_date = floor($this->expired_time / 86400) . "天";
        } elseif ($this->expired_time < 31536000) {
            $expired_date = floor($this->expired_time / 2592000) . "个月";
        } else {
            $expired_date = floor($this->expired_time / 31536000) . "年";
        }

        $this->setAttribute("expired_date", $expired_date);

        return $expired_date;
    }

    public function getImageUrlAttribute()
    {
        $this->imageHandle($this->image_url);
    }
}
