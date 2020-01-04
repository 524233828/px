<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-01-01
 * Time: 11:48
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{

    protected $table = "px_banner";


    public static function fetchUseful()
    {
        return self::query()->where([["status", "=", "1"]])->get();
    }

    public function getImgUrlAttribute($value)
    {
        $url = config("app.resource_url");
        return $url . DIRECTORY_SEPARATOR .$value;
    }

}