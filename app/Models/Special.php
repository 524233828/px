<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-03-14
 * Time: 10:50
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Special
 * @package App\Models
 * @property Collection $specialClass
 */
class Special extends Model
{

    protected $table = "px_special";

    public $timestamps = false;

    public function specialClass()
    {
        return $this->hasMany(SpecialClass::class, "special_id", "id");
    }

    public function getTotalTimeAttribute()
    {
        $total_time = 0;
        $this->specialClass->map(function (SpecialClass $specialClass) use (&$total_time){
            $total_time += $specialClass->attributes['total_time'];
        });

        if($total_time < 60){
            $str = $total_time . "秒";
        }elseif($total_time < 3600){
            $str = floor($total_time/60) . "分" . ($total_time % 60) . "秒";
        }else{
            $str = floor($total_time/3600) . "小时" . floor(($total_time % 3600) / 60) . "分";
        }

        return $str;

    }
}
