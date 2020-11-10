<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-11-10
 * Time: 11:38
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class WeekTime extends Model
{

    protected $table = "px_class_week_time";

    protected $fillable = ["class_id", "week", "time"];

    public function classes(){
        return $this->belongsTo(Classes::class, "class_id", "id");
    }
}
