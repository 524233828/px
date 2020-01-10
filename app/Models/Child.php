<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-01-10
 * Time: 16:59
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Child extends Model
{

    protected $table = "px_child";

    protected $fillable = ["uid", "name", "tel", "birth", "gender"];
}