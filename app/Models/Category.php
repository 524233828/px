<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

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
}