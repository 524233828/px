<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-01-09
 * Time: 19:09
 */

namespace App\Api\Controllers;


use App\Libraries\RegisterHandler;
use App\Models\Card;
use App\Models\CardOrder;
use App\Models\Child;
use App\Models\Image;
use App\Models\Order;
use App\Models\PxUser;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use JoseChan\Base\Api\Controllers\Controller;
use JoseChan\Pager\Pager;
use JoseChan\UserLogin\Constants\User;
use JoseChan\UserLogin\Libraries\Wechat\MiniProgram\Application;

/**
 * 用户相关
 * Class UserController
 * @package App\Api\Controllers
 */
class UserController extends Controller
{

    use Image;

    /**
     * 用户信息
     * @return \Illuminate\Http\JsonResponse
     */
    public function info()
    {

        //获取钱包
        /** @var PxUser $user */
        $user = User::$info;
        /** @var Wallet $wallet */
        $wallet = Wallet::query()->where("uid", "=", $user->id)->first();
        $return = $user->getFrontFields();

        $card_order = CardOrder::getUsefulCard();
        $vip_level = CardOrder::getUserVipLevelByCardOrder($card_order);
        $expired_time = CardOrder::getVipExpiredByCardOrder($card_order);
        $card = Card::query()->find($vip_level);

        $return["amount"] = $wallet->amount;
        if ($card) {
            $return["vip_icon"] = $card->icon;
            $return["expired_time"] = date("Y-m-d H:i:s", $expired_time);
        } else {
            $return["vip_icon"] = "";
            $return["expired_time"] = 0;
        }

        if ($user->pid != 0) {
            /** @var PxUser $parent */
            $parent = $user->parent;

            $return ['parent_name'] = $parent->nickname;
        } else {
            $return ['parent_name'] = "";
        }


        return $this->response($return);
    }

    /**
     * 用户邀请码
     * @return \Illuminate\Http\JsonResponse
     */
    public function code()
    {
        /** @var PxUser $user */
        $user = User::$info;

        //获取配置
        $config = config("user_login");

        $code = $user->getAttribute("code");

        $mini_program = new Application($config['mini_program']['app_id'], $config['mini_program']['app_secret']);

        $image = $mini_program->bindRedis(Redis::connection()->client())->getWxaCodeUnLimit("invited_{$code}");

        $file_name = "user/invited_code/{$user->id}.jpg";

        if (is_numeric($image) || !Storage::disk("admin")->put($file_name, $image)) {
            return $this->response([], 6000, "生成小程序码失败");
        }

        return $this->response(["code" => $code, "code_image" => $this->imageHandle($file_name)]);

    }

    public function update(Request $request)
    {
        $this->validate($request->all(), [
            "encryptedData" => "required",
            "iv" => "required",
        ]);

        $encryptedData = $request->get("encryptedData");
        $iv = $request->get("iv");

        //获取配置
        $config = config("user_login");
        $mini_program = new Application($config['mini_program']['app_id'], $config['mini_program']['app_secret']);

        $json = $mini_program->encryptedDataDecode($encryptedData, User::$extra['session_key'], $iv);

        if (!$data = json_decode($json, true)) {
            return $this->response([], 6002, "数据格式不正确");
        }

        /** @var PxUser $user */
        $user = User::$info;
        if (isset($data['nickName'])) {
            $user->nickname = $data['nickName'];
        }

        if (isset($data['avatarUrl'])) {
            $user->headimg_url = $data['avatarUrl'];
        }

        if (isset($data['avatarUrl'])) {
            $user->headimg_url = $data['avatarUrl'];
        }

        if (isset($data['phoneNumber'])) {
            $user->phone_number = $data['phoneNumber'];
        }

        if ($user->save()) {
            return $this->response([]);
        } else {
            return $this->response([], 6003, "更新用户信息失败");
        }

    }

    public function bindCode(Request $request)
    {
        $this->validate($request->all(), [
            "code" => "required"
        ]);

        $code = (int)$request->get("code");

        $parent = PxUser::query()->find($code);

        if ($code == 0 || !$parent) {
            return $this->response([], 6004, "无效邀请码");
        }

        /** @var PxUser $user */
        $user = User::$info;

        if ($user->pid != 0) {
            return $this->response([], 6005, "已绑定过邀请码");
        }

        if ($user->id == $code) {
            return $this->response([], 6006, "不能绑定自己的邀请码");
        }

        $user->pid = $code;

        if ($user->save()) {
            return $this->response([]);
        }

        return $this->response([], 6007, "绑定邀请码失败");
    }

    public function isBind(Request $request)
    {
        /** @var PxUser $user */
        $user = User::$info;

        if ($user->pid != 0) {
            return $this->response(["is_bind" => 1]);
        }

        return $this->response(["is_bind" => 0]);
    }

    public function fetchChildren(Request $request)
    {

        /** @var PxUser $user */
        $user = User::$info;

        $page = $request->get("page", 1);
        $size = $request->get("size", 20);

        $count = PxUser::query()->where("pid", "=", $user->id)->count();
        $pager = new Pager($page, $size);

        $children = PxUser::query()->where("pid", "=", $user->id)
            ->offset($pager->getFirstIndex())
            ->limit($size)
            ->get(["nickname", "headimg_url", "id"]);

        $children->map(function (PxUser $item) {
            $item->setAttribute("code", $item->code);
        });

        return $this->response(["list" => $children, "meta" => $pager->getPager($count)]);
    }

    /**
     * 检查用户是否注册
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkUser(Request $request)
    {
        $union_id = $request->get("union_id", "");

        if (empty($union_id)) {
            return $this->response([], 6008, "union_id不能为空");
        }

        /** @var PxUser $user */
        $user = PxUser::query()->where("union_id", "=", $union_id)->first();
        if (empty($user) || !$user->exists) {
            return $this->response(["is_register" => 0]);
        }

        //已注册判断是否会员
        $level = CardOrder::getUserVipLevelByUserId($user->id);

        $data = ["is_register" => 1];

        if ($level > 0) {
            $data["is_vip"] = 1;
            $data["vip_level"] = $level;
        } else {
            $data["is_vip"] = 0;
            $data["vip_level"] = 0;
        }

        return $this->response($data);
    }

    /**
     * 注册用户
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function register(Request $request)
    {
        $access_token = $request->post("access_token", "");
        $open_id = $request->post("open_id", "");
        $order_sn = $request->post("order_sn", "");

        if (empty($access_token) || empty($open_id) || empty($order_sn)) {
            return $this->response([], 6009, "参数不能为空");
        }

        //请求微信获取用户信息
        $url = "https://api.weixin.qq.com/sns/userinfo?access_token={$access_token}&openid={$open_id}&lang=zh_CN";
        $json = file_get_contents($url);
        if (empty($json)) {
            return $this->response([], 6010, "拉取用户信息失败");
        }

        $result = json_decode($json, true);

        if (empty($result)) {
            return $this->response([], 6010, "拉取用户信息失败");
        }

        $union_id = isset($result['unionid']) ? $result['unionid'] : "";

        if (empty($union_id)) {
            return $this->response([], 6010, "拉取用户信息失败");
        }

        $user = PxUser::query()->where("union_id", "=", $union_id)->first();
        if (empty($user) || !$user->exists) {
            $user_info = [
                "unionid" => $union_id,
                "openid" => ""
            ];

            $user = (new RegisterHandler())->handler($user_info);

            if (!$user) {
                return $this->response([], 6011, "注册失败");
            }
        }
//        else {
//            return $this->response([], 6012, "已注册过，请勿重复注册");
//        }

        //判断用户会员
        //已注册判断是否会员
        $level = CardOrder::getUserVipLevelByUserId($user->id);

        if ($level > 0) {
            return $this->response([], 6012, "已注册过，请勿重复注册");
        }

        /** @var Card $card */
        $card = Card::query()->find(1);
        //否则创建
        $connection = $user->getConnection();

        $connection->beginTransaction();

        //创建订单
        $order = new Order([
            "order_sn" => $order_sn,
            "uid" => $user->id,
            "type" => \App\Libraries\OrderHandler\Order::CARD,
            "money" => 0,
            "status" => 1,
            "info" => "平台全场通用预约券 - 小学堂购买",
        ]);


        //创建小朋友
        //获取用户的小朋友
        /** @var Child $child */
        $child = Child::query()->where('uid', "=", $user->id)->first();
        if (!empty($child) && $child->exists) {
            //创建会员卡
            $card_order = new CardOrder([
                "user_id" => $user->id,
                "order_sn" => $order_sn,
                "child_name" => $child->name,
                "child_tel" => $child->tel,
                "child_birth" => $child->birth,
                "child_gender" => $child->gender,
                "buy_time" => time(),
                "status" => 1,
                "expired_time" => time() + $card->expired_time,
                "card_id" => 1,
            ]);
        } else {
            $card_order = new CardOrder([
                "user_id" => $user->id,
                "order_sn" => $order_sn,
                "child_name" => isset($result['nickname']) ? $result['nickname'] : "",
                "child_tel" => "",
                "child_birth" => "",
                "child_gender" => isset($result['sex']) ? $result['sex'] : 1,
                "buy_time" => time(),
                "status" => 1,
                "expired_time" => time() + $card->expired_time,
                "card_id" => 1,
            ]);
        }

        if (!$order->save() || !$card_order->save()) {
            $connection->rollBack();
            return $this->response([], 6013, "创建会员失败");
        }

        $connection->commit();

        return $this->response([]);

    }

}