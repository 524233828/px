<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Withdraw extends Model
{
    protected $table = "px_withdraw";

    protected $fillable = [
        "uid",
        "money",
        "status",
        "order_sn",
        "remark"
    ];

    public static function getWithdrawSn()
    {
        return (string)(microtime(true) * 10000);
    }

}