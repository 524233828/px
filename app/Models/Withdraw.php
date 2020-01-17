<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * 提现模型
 * Class Withdraw
 * @package App\Models
 * @property integer $id
 * @property integer $uid
 * @property float $money
 * @property integer $status
 * @property string $order_sn
 * @property string $remark
 * @property string $created_at
 * @property string $updated_at
 */
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