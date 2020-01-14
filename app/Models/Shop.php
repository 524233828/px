<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-01-05
 * Time: 18:01
 */

namespace App\Models;


use Encore\Admin\Auth\Database\Administrator;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use Image;

    protected $table = "px_shop";

    public function business()
    {
        return $this->belongsTo(Administrator::class, "admin_id", "id");
    }

    public function getHeadimgUrlAttribute($value)
    {
        return $this->imageHandle($value);
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
}