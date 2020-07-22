<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-01-04
 * Time: 16:29
 */

namespace App\Api\Controllers;

use App\Libraries\OrderHandler\Order;
use App\Models\Order as OrderModel;
use App\Libraries\PaymentHandler\Payment;
use Illuminate\Http\Request;
use JoseChan\Base\Api\Controllers\Controller;
use JoseChan\Pager\Pager;
use JoseChan\UserLogin\Constants\User;
use Runner\NezhaCashier\Cashier;

/**
 * 订单相关
 * Class OrderController
 * @package App\Api\Controllers
 */
class OrderController extends Controller
{

    /**
     * 下单
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function unifier(Request $request)
    {
        $with_pay = $request->get("with_pay", true);
        $pay_type = $request->get("pay_type", Payment::WECHAT_MINIPROGRAM);
        $order_data = $request->get("order_data");
        $order_type = $request->get("order_type");

        $this->validate($request->all(), [
            "order_type" => "required|in:0,1,2,3",
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

    /**
     * 回调
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function notify(Request $request)
    {
        $log = myLog("order_controller_notify");
        $config = config("payment");
        $pay = new Cashier(Payment::WECHAT_MINIPROGRAM, $config[Payment::WECHAT_MINIPROGRAM]);

        $form = $pay->notify("charge");

        $log->debug("form:".json_encode($form->all()));

        if ($form->get("status") === "paid") {
            if(Payment::notify($form->get('order_id'))){
                echo $pay->success();
                die();
            }
        }

        return $this->response([]);
    }

    /**
     * 支付
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
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

        if(strtotime($order->created_at) + 3600 < time()){
            return $this->response([], 2006, "支付失败：订单已过期请重新购买");
        }

        $payment = Payment::unified($order, $pay_type);

        $return['payment'] = $payment;

        return $this->response($return);
    }


    public function fetch(Request $request)
    {
        $status = $request->get("status", null);
        $page = $request->get("page", 1);
        $size = $request->get("size", 20);
        $where = [["uid", "=", User::$info['id']]];
        if ($status !== null) {
            $where[] = ["status", "=", $status];
        }

        $pager = new Pager($page, $size);

        $count = OrderModel::query()->where($where)->count();

        $order = OrderModel::query()->where($where)
            ->offset($pager->getFirstIndex())
            ->limit($size)
            ->get();


        if ($order->isEmpty()) {
            return $this->response([
                "list" => [], "meta" => $pager->getPager(0)
            ], 1, "暂无订单");
        }

        $order->map(function(OrderModel $item){
            $item->classOrder;
        });

        return $this->response(["list" => $order, "meta" => $pager->getPager($count)]);
    }

    public function get(Request $request)
    {
//        $this->validate($request->all(), [
//            "order_id" => "required|integer",
//        ]);

        $order_id = $request->get("order_id", null);

        $order_sn = $request->get("order_sn", null);

        if(empty($order_id) && empty($order_sn)){
            return $this->response([], 2005, "订单不存在");
        }

        if(!empty($order_id)){
            /** @var OrderModel $order */
            $order = OrderModel::query()->find($order_id);
        }else{
            $order = OrderModel::query()->where("order_sn", "=", $order_sn)->first();
        }

        if(!$order){
            return $this->response([], 2005, "订单不存在");
        }

        $order->classOrder;

        return $this->response($order->toArray());

    }
}