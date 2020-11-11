<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-01-17
 * Time: 14:12
 */

namespace App\Api\Controllers;


use App\Libraries\PaymentExtensions\Gateway\Wechat\Transfers;
use App\Models\Bill;
use App\Models\Config;
use App\Models\PxUser;
use App\Models\Wallet;
use App\Models\Withdraw;
use Illuminate\Http\Request;
use JoseChan\Base\Api\Controllers\Controller;
use JoseChan\UserLogin\Constants\User;
use Runner\NezhaCashier\Cashier;
use Runner\NezhaCashier\Utils\Amount;

class WalletController extends Controller
{

    /**
     * 获取钱包信息
     * @return \Illuminate\Http\JsonResponse
     */
    public function get()
    {
        /** @var PxUser $uesr */
        $uesr = User::$info;
        //获取钱包
        $wallet = Wallet::query()->where("uid", "=", $uesr->id)->first();

        return $this->response(["wallet" => $wallet]);
    }

    /**
     * 提现
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function withdraw(Request $request)
    {
        $this->validate($request->all(), [
            "money" => "required|numeric|max:5000|min:1"
        ]);

        $money = $request->get("money");
        $remark = $request->get("remark", "");

        /** @var PxUser $user */
        $user = User::$info;

        //读取用户钱包
        $wallet = Wallet::query()->where("uid", "=", $user->id)->first();

        if (!$wallet) {
            return $this->response([], 7000, "操作失败");
        }

        if ($wallet->amount < $money) {
            return $this->response([], 7001, "钱包余额不足");
        }

        $wallet->getConnection()->beginTransaction();

        $order_sn = Withdraw::getWithdrawSn();

        //生成提现记录
        $withdraw = new Withdraw([
            "uid" => $user->id,
            "money" => $money,
            "status" => 0,
            "order_sn" => $order_sn,
            "remark" => $remark
        ]);

        //操作钱包
        $wallet->amount -= $money;

        //是否允许直接提现
        if (Config::get("is_withdraw", 0)) {
            //允许
            $config = new \Runner\NezhaCashier\Utils\Config(config("payment.wechat_mina"));

            $transfer = new Transfers($config);
//            /** @var Cashier $pay */
//            $cashier = new Cashier("wechat_transfer", config("payment.wechat_mina"));

            //生成账单
            $bill = new Bill([
                "bill_no" => Bill::getBillSn(),
                "out_trade_type" => Bill::OUT_TYPE_WITHDRAW,
                "out_trade_no" => $order_sn,
                "type" => Bill::TYPE_OUTCOME,
                "money" => $money,
                "remark" => "提现金额",
                "uid" => $user->id
            ]);

            //组装参数
            $data = [
                "order_sn" => $order_sn,
                "openid" => $user->open_id,
                "amount" => $money,
                "desc" => "用户提现",
                "ip" => client_ip(0, true)
            ];

            if (!$wallet->save()) {
                return $this->response([], 7001, "钱包扣款失败");
            }

            try {
                $result = $transfer->pay($data);

                if ($result['return_code'] == "SUCCESS") {
                    //转账成功
                    $bill->save();

                    $withdraw->status = 1;
                    $withdraw->save();

                    $wallet->getConnection()->commit();

                    return $this->response([]);
                }
            } catch (\Exception $exception) {
                $wallet->getConnection()->rollBack();
                return $this->response([], 7001, "转出金额失败");
            }

        } else {
            //冻结
            $wallet->freeze_amount += $money;

            $bill = new Bill([
                "bill_no" => Bill::getBillSn(),
                "out_trade_type" => Bill::OUT_TYPE_WITHDRAW,
                "out_trade_no" => $order_sn,
                "type" => Bill::TYPE_OUTCOME,
                "money" => Amount::dollarToCent($money),
                "remark" => "提现金额",
                "uid" => $user->id
            ]);

            if (!$wallet->save()) {
                return $this->response([], 7001, "冻结金额失败");
            }

        }


        if ($bill->save() && $withdraw->save()) {
            $wallet->getConnection()->commit();

            return $this->response([], 1, "操作成功，请等待人工审核");
        } else {
            $wallet->getConnection()->rollBack();

            return $this->response([], 7002, "操作失败");
        }

    }
}