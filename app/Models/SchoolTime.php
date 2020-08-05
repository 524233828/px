<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-08-05
 * Time: 15:02
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * Class SchoolTime
 * @package App\Models
 * @property integer $class_id 课程ID
 * @property string $start_time 上课时间
 * @property Classes $classes
 */
class SchoolTime extends Model
{

    protected $table = "px_class_school_times";

    protected $fillable = ["class_id", "start_time"];

    public function classes(){
        return $this->belongsTo(Classes::class, "class_id", "id");
    }
}
