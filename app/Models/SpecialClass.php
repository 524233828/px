<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-03-14
 * Time: 11:33
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class SpecialClass extends Model
{

    protected $table = "px_special_class";

    public $timestamps = false;

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, "teacher_id", "id");
    }

    public function removeVideoUrl()
    {
        unset($this->attributes['video_url']);
        return $this;
    }
}
