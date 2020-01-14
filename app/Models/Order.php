<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-01-05
 * Time: 15:01
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * Class Order
 * @package App\Models
 * @property string $order_sn 订单号
 * @property integer $uid 用户ID
 * @property integer $type 订单类型
 * @property float $money 订单价格
 * @property string $created_at 创建时间
 * @property string $updated_at 更新时间
 * @property string $pay_sn 支付号
 * @property integer $status 状态
 */
class Order extends Model
{
    protected $table = "px_order";

    protected $fillable = ["order_sn", "uid", "money", "type"];

    public static function getOrderSn()
    {
        return (string)(microtime(true)*10000);
    }

    public static function getPaySn()
    {
        return (string)(microtime(true)*10000);
    }
}