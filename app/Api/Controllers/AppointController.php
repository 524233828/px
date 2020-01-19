<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-01-14
 * Time: 15:51
 */

namespace App\Api\Controllers;


use App\Models\Appoint;
use App\Models\CardOrder;
use App\Models\Classes;
use Carbon\Carbon;
use Illuminate\Http\Request;
use JoseChan\Base\Api\Controllers\Controller;
use JoseChan\UserLogin\Constants\User;

/**
 * 预约相关
 * Class AppointController
 * @package App\Api\Controllers
 */
class AppointController extends Controller
{
    /**
     * 预约课程
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $this->validate($request->all(), [
            "class_id" => "required|integer",
            "card_id" => "required|integer",
        ]);

        $class_id = $request->get("class_id");
        $card_id = $request->get("card_id");

        /** @var CardOrder $card */
        $card = CardOrder::find($card_id);

        /** @var Classes $class */
        $class = Classes::find($class_id);

        if (!$card || !$class) {
            return $this->response([], 3001, "课程或卡券不存在");
        }

        if ($card->status != 1) {
            return $this->response([], 3002, "卡券未购买");
        }

        $now = now();
        if($now->gt($class->start_time)){
            return $this->response([], 3006, "课程已过了上课时间");
        }

        $appoint = Appoint::where([
            ["class_id", "=", $class_id],
            ["card_id", "=", $card_id],
        ]);

        if($appoint){
            return $this->response([], 3005, "已预约过该课程，请耐心等待上课");
        }

        //检查卡券预约过多少次该商户的课程
        $appoint_num = Appoint::countBusinessCardAppointNum($class->shop->admin_id, $card_id);
        if ($appoint_num >= 3) {
            return $this->response([], 3003, "同一个商户只能预约三次");
        }

        //创建预约单
        $appoint = new Appoint([
            "shop_id" => $class->shop_id,
            "uid" => User::$info['id'],
            "class_id" => $class_id,
            "status" => 0,
            "card_id" => $card_id,
            "admin_id" => $class->shop->admin_id
        ]);

        if($appoint->save()){
            return $this->response([]);
        }

        return $this->response([], 3004, "预约失败");
    }

    public function fetch()
    {
        $appoint = Appoint::query()->where("uid", "=", User::$info['id'])->first();
        
        $appoint->classes;

        if(!$appoint){
            return $this->response([], 2002, "暂无预约");
        }

        return $this->response($appoint->toArray());
    }
}