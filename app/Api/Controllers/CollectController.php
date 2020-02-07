<?php
/**
 * Created by PhpStorm.
 * User: lao
 * Date: 2020/1/17
 * Time: 10:25
 */

namespace App\Api\Controllers;

use App\Collections\CollectCollection;
use App\Models\Collect;
use App\Models\PxUser;
use JoseChan\Base\Api\Controllers\Controller;
use Illuminate\Http\Request;
use JoseChan\Pager\Pager;
use JoseChan\UserLogin\Constants\User;

class CollectController extends Controller
{
    public function create(Request $request)
    {
        $this->validate($request->all(), [
            "business_id" => "required",
            "type" => "required|in:1,2",
        ]);

        $business_id = $request->get("business_id");
        $type = $request->get("type");

        /** @var PxUser $user */
        $user = User::$info;

        $collect_res = Collect::where([
            ["business_id", "=", $business_id],
            ["type", "=", $type],
            ["uid", "=", $user],
        ])->get();

        if ($collect_res->isNotEmpty()) {
            return $this->response([], 2000, "你已收藏");
        }

        $collect = new Collect(["uid" => User::$info['id']]);

        $collect->business_id = $business_id;
        $collect->type = $type;

        if ($collect->save()) {
            return $this->response([]);
        }else{
            return $this->response([], 2001, "保存失败");
        }

    }

    public function cancel(Request $request)
    {
        $this->validate($request->all(), [
            "business_id" => "required",
            "type" => "required|in:1,2",
        ]);

        $business_id = $request->get("business_id");
        $type = $request->get("type");

        $collect_res = Collect::query()->where([["business_id", "=", $business_id], ["type", "=", $type]])->first();

        if (!$collect_res) {
            return $this->response([], 2000, "找不到数据");
        }
        ;
        if ($collect_res->delete()) {
            return $this->response([]);
        }else{
            return $this->response([], 2001, "取消收藏失败");
        }
    }

    public function fetch(Request $request)
    {
        $this->validate($request->all(), [
            "type" => "required|in:1,2",
        ]);
        $type = $request->get("type");
        $page = $request->get("page", 1);
        $size = $request->get("size", 20);
        $latitude = $request->get("latitude", 0);
        $longitude = $request->get("longitude", 0);
        $where = [["uid", "=", User::$info['id']], ["type", "=", $type]];

//        /** @var CollectCollection $collects 获取收藏 */
//        $collects = Collect::query()->where([["uid", "=", User::$info['id']], ["type", "=", $type])->get();


        $pager = new Pager($page, $size);

        $count = Collect::query()->where($where)->count();

        $collects = Collect::query()->where($where)
            ->offset($pager->getFirstIndex())
            ->limit($size)
            ->get();

        if(!$collects){
            return $this->response([], 2002, "暂无收藏");
        }
        
        if ($type == 1) {
            $collects->map(function(Collect $item){
                $item->shop->computeCommentsInfo();
            });
        } else {
            $collects->map(function(Collect $item) use ($latitude, $longitude){
                $item->classes->shop->computeCommentsInfo();

                if (!empty($latitude) && !empty($longitude)) {
                    $item->classes->shop->computeDistance($latitude, $longitude);
                }
            });
        }
        return $this->response(["list" => $collects, "meta" => $pager->getPager($count)]);
    }
}