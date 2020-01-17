<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-01-16
 * Time: 18:10
 */

namespace App\Libraries\PaymentHandler;


use App\Models\Bill;
use App\Models\Config;
use App\Models\Order;
use App\Models\PxUser;
use App\Models\Wallet;

/**
 * 分销处理类
 * Class Distribution
 * @package App\Libraries\PaymentHandler
 */
class Distribution
{

    /**
     * 分销逻辑
     * @param Order $order
     * @return bool
     * @throws \Exception
     */
    public static function handler(Order $order)
    {
        /** @var PxUser $user */
        $user = PxUser::find($order->uid);

        //用户有上级，计算分销
        if ($user->pid != 0) {
            //需要记录分销记录表，流水表，钱包表
            //先找到用户
            /** @var PxUser $distribution_user */
            $distribution_user = PxUser::find($user->pid);

            //用户不存在，不分销
            if(!$distribution_user){
                throw new \Exception("用户不存在");
            }

            //找到用户的钱包
            $wallet = Wallet::query()->where("uid", "=", $distribution_user->id)->first();

            //没有钱包
            if(!$wallet){
                throw new \Exception("钱包获取失败");
            }

            //分销比例
            $distribution_rate = Config::get("distribution_rate", 0);

            //奖励金额
            $amount = bcmul($order->money, $distribution_rate, 2);

            //创建分销记录
            $distribution = new \App\Models\Distribution([
                "uid" => $distribution_user->id,
                "order_sn" => $order->order_sn,
                "remark" => "分销",
                "total_amount" => $order->money,
                "percent" => $distribution_rate,
                "amount" => $amount
            ]);

            //创建账单流水
            $bill = new Bill([
                "bill_no" => Bill::getBillSn(),
                "out_trade_type" => Bill::OUT_TYPE_DISTRIBUTION,
                "out_trade_no" => $order->order_sn,
                "type" => Bill::TYPE_INCOME,
                "money" => $amount,
                "remark" => "分销获得",
                "uid" => $distribution_user->id
            ]);

            //修改钱包
            $wallet->amount += $amount;

            //开始事务
            $wallet->getConnection()->beginTransaction();

            if($wallet->save() && $distribution->save() && $bill->save()){
                $wallet->getConnection()->commit();

                return true;
            }else{
                throw new \Exception("保存分销记录失败");
            }
        }
    }
}