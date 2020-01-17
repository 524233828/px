<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-01-01
 * Time: 11:48
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * 首页banner
 * Class Banner
 * @package App\Models
 * @property integer $id ID
 * @property string $name 名字
 * @property string $img_url 图片
 * @property string $link 链接
 * @property integer $status 状态
 * @property string $created_at 创建时间
 * @property string $updated_at 更新时间
 */
class Banner extends Model
{

    use Image;

    protected $table = "px_banner";

    public static function fetchUseful()
    {
        return self::query()->where([["status", "=", "1"]])->get();
    }

    public function getImgUrlAttribute($value)
    {
        return $this->imageHandle($value);
    }

}