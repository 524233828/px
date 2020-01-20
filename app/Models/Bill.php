<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-01-17
 * Time: 11:12
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * 账单
 * Class Bill
 * @package App\Models
 * @property integer $id ID
 * @property string $bill_no 账单号
 * @property integer $out_trade_type 外部订单类型
 * @property string $out_trade_no 外部订单号
 * @property integer $type 类型
 * @property float $money 金额
 * @property string $remark 备注
 * @property integer $uid 用户ID
 * @property string $created_at 创建时间
 * @property string $updated_at 更新时间
 */
class Bill extends Model
{
    protected $table = "px_bill";

    // 外部单号类型
    /** @var int OUT_TYPE_DISTRIBUTION 分销类型 */
    public const OUT_TYPE_DISTRIBUTION = 1;
    /** @var int OUT_TYPE_WITHDRAW 提现类型 */
    public const OUT_TYPE_WITHDRAW = 2;

    // 账单类型
    /** @var int TYPE_FREEZE 冻结 */
    public const TYPE_FREEZE = 0;
    /** @var int TYPE_OUTCOME 支出 */
    public const TYPE_OUTCOME = 1;
    /** @var int TYPE_INCOME 收入 */
    public const TYPE_INCOME = 2;

    protected $fillable = ["bill_no", "out_trade_type", "out_trade_no", "type", "money", "remark", "uid"];

    public function user(){
        return $this->belongsTo(PxUser::class, "uid", "id");
    }

    /**
     * 生成订单流水号
     * @return string
     */
    public static function getBillSn()
    {
        return (string)(microtime(true)*10000);
    }
}