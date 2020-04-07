<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-01-17
 * Time: 12:09
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    protected $table = "px_config";

    public function getValueAttribute($value)
    {
        if (!empty($this->callback)) {
            return call_user_func($this->callback, $value);
        }
    }

    public static function get($key, $default = null)
    {
        $config = self::query()->where("key", "=", $key)->first();

        if (!$config) {
            return $default;
        }

        return $config->getAttribute("value");
    }

    public function setValueAttribute($value)
    {
        if($value == null){
            return "";
        }
        return $value;
    }
}