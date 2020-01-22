<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * Class Category
 * @package App\Models
 * @property integer $id 名字
 * @property string $name 名字
 * @property string $created_at 创建时间
 * @property string $updated_at 更新时间
 * @property string $parent_id 父类ID
 * @property string $status 状态
 * @property string $img_url 图片
 */
class Category extends Model
{
    use Image;
    protected $table = "px_category";

    public static function getSelector()
    {
        $category = self::query()->where("parent_id", "<>", 0)->get();

        $category_list = [0=>"请选择"];
        foreach ($category as $value) {
            $category_list[$value->id] = $value->name;
        }

        return $category_list;
    }

    public function getImgUrlAttribute($value)
    {
        return $this->imageHandle($value);
    }

    public function getChildren()
    {
        return Category::query()->where("parent_id", "=", $this->id)->get();
    }
}