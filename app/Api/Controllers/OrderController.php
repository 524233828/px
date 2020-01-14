<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-01-04
 * Time: 16:29
 */

namespace App\Api\Controllers;

use App\Libraries\OrderHandler\Order;
use App\Libraries\PaymentHandler\Payment;
use Illuminate\Http\Request;
use JoseChan\Base\Api\Controllers\Controller;
use Runner\NezhaCashier\Cashier;

class OrderController extends Controller
{

    public function unifier(Request $request)
    {
        $with_pay = $request->get("with_pay", true);
        $pay_type = $request->get("pay_type", Payment::WECHAT_MINIPROGRAM);
        $order_data = $request->get("order_data");
        $order_type = $request->get("order_type");

        $this->validate($request->all(), [
            "order_type" => "required|in:0,1,2",
            "order_data" => "required"
        ]);


        try {
            //下单
            $order = Order::create($order_type, $order_data);

            $return = ["order_sn" => $order->order_sn];

            //如果需要支付同时返回支付信息，否则只返回订单号
            if ($with_pay) {
                $payment = Payment::unified($order, $pay_type);

                $return['payment'] = $payment;
            }

            return $this->response($return);

        } catch (\Exception $exception) {
            return $this->response([], 2000, "下单失败：{$exception->getMessage()}");
        }
    }

    public function notify(Request $request)
    {
        $config = config("payment");
        $pay = new Cashier(Payment::WECHAT_MINIPROGRAM, $config[Payment::WECHAT_MINIPROGRAM]);

        $form = $pay->notify("charge");

        if ($form->get("status") === "paid") {
            Payment::notify($form->get('trade_sn'));
        }

        return $this->response([]);
    }

    public function pay(Request $request)
    {

        $this->validate($request->all(), ["order_sn" => "required"]);
        $order_sn = $request->get("order_sn");
        $pay_type = $request->get("pay_type", Payment::WECHAT_MINIPROGRAM);
        //下单
        /** @var \App\Models\Order $order */
        $order = \App\Models\Order::query()->where("order_sn", "=", $order_sn)->first();

        if (!$order) {
            return $this->response([], 2001, "订单不存在");
        }

        $payment = Payment::unified($order, $pay_type);

        $return['payment'] = $payment;

        return $this->response($return);
    }
}