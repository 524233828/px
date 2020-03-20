<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-03-14
 * Time: 11:33
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Class SpecialClass
 * @package App\Models
 * @property SpecialClassOrder|Collection $specialClassOrder
 */
class SpecialClass extends Model
{

    protected $table = "px_special_class";

    public $timestamps = false;

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, "teacher_id", "id");
    }

    public function specialClassOrder(){
        return $this->hasMany(SpecialClassOrder::class, "class_id", "id");
    }

    public function removeVideoUrl()
    {
        unset($this->attributes['video_url']);
        return $this;
    }

    public function getTotalTimeAttribute()
    {
        $total_time = $this->attributes['total_time'];
        if($total_time < 60){
            $str = $total_time . "秒";
        }elseif($total_time < 3600){
            $str = floor($total_time/60) . "分" . ($total_time % 60) . "秒";
        }else{
            $str = floor($total_time/3600) . "小时" . floor(($total_time % 3600) / 60) . "分";
        }

        return $str;
    }

    public function isLike(PxUser $user)
    {
        $count = Like::query()->where([
            ["business_id", "=", $this->id],
            ["type", "=", Like::TYPE_SPECIAL_CLASS],
            ["uid", "=", $user->id]
        ])->count();

        $this->setAttribute("is_like", $count);

        return $this;
    }

}
