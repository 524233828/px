<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-01-22
 * Time: 20:02
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class ClassOrder extends Model
{

    protected $table = "px_class_order";

    protected $fillable = ["user_id", "order_sn", "class_id"];
}