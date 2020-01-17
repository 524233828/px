<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-01-17
 * Time: 11:12
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    protected $table = "px_bill";

    public const OUT_TYPE_DISTRIBUTION = 1;
    public const OUT_TYPE_WITHDRAW = 2;

    public const TYPE_FREEZE = 0;
    public const TYPE_OUTCOME = 1;
    public const TYPE_INCOME = 2;

    protected $fillable = ["bill_no", "out_trade_type", "out_trade_no", "type", "money", "remark", "uid"];

    public static function getBillSn()
    {
        return (string)(microtime(true)*10000);
    }
}