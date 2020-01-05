<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-01-05
 * Time: 15:01
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public static function getOrderSn()
    {
        return microtime(true)*10000;
    }

    public static function getPaySn()
    {
        return microtime(true)*10000;
    }
}