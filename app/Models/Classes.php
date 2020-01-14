<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-01-04
 * Time: 14:59
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    use Image;

    protected $table = "px_class";

    public function shop()
    {
        return $this->belongsTo(Shop::class, "shop_id", "id");
    }

    public function getPicAttribute($value)
    {
        return $this->imageHandle($value);
    }

}