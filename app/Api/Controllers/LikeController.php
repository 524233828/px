<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-03-20
 * Time: 09:57
 */

namespace App\Api\Controllers;


use App\Models\Classes;
use App\Models\Like;
use App\Models\PxUser;
use App\Models\Shop;
use App\Models\SpecialClass;
use Illuminate\Http\Request;
use JoseChan\Base\Api\Controllers\Controller;
use JoseChan\UserLogin\Constants\User;

class LikeController extends Controller
{
    public function create(Request $request)
    {
        $this->validate($request->all(), [
            "business_id" => "required",
        ]);

        $business_id = $request->get("business_id");
        $type = $request->get("type");

        if(!isset(Like::$business_model[$type])){
            return $this->response([], 2003, "类型不存在");
        }

        $model = Like::$business_model[$type];
        /** @var Shop|Classes|SpecialClass $obj */
        $obj = $model::query()->where("id", "=", $business_id)->get()->first();

        if(!$obj){
            return $this->response([], 2003, "业务ID不存在");
        }

        /** @var PxUser $user */
        $user = User::$info;

        $collect_res = Like::query()->where([
            ["business_id", "=", $business_id],
            ["type", "=", $type],
            ["uid", "=", $user->id],
        ])->get();

        if ($collect_res->isNotEmpty()) {
            return $this->response([], 2000, "你已点赞");
        }

        $collect = new Like(["uid" => $user->id]);

        $collect->business_id = $business_id;
        $collect->type = $type;

        $obj->like += 1;

        if ($collect->save() && $obj->save()) {
            return $this->response([]);
        }else{
            return $this->response([], 2001, "保存失败");
        }
    }
}
