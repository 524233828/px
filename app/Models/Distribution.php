<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-01-17
 * Time: 11:13
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Distribution extends Model
{
    protected $table = "px_distribution";

    protected $fillable = ["order_sn", "uid", "remark", "total_amount", "percent", "amount"];
}