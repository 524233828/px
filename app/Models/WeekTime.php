<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-11-10
 * Time: 11:38
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * Class WeekTime
 * @package App\Models
 * @property int $week
 * @property string $time
 */
class WeekTime extends Model
{
    protected $table = "px_class_week_time";

    protected $fillable = ["class_id", "week", "time"];

    public static $week_name = [
        1 => "星期一",
        2 => "星期二",
        3 => "星期三",
        4 => "星期四",
        5 => "星期五",
        6 => "星期六",
        7 => "星期日",
    ];

    public function classes(){
        return $this->belongsTo(Classes::class, "class_id", "id");
    }
}
