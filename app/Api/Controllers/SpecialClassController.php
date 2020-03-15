<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-03-14
 * Time: 11:35
 */

namespace App\Api\Controllers;


use App\Models\CardOrder;
use App\Models\SpecialClass;
use App\Models\SpecialClassOrder;
use function foo\func;
use Illuminate\Http\Request;
use JoseChan\Base\Api\Controllers\Controller;
use JoseChan\Pager\Pager;

class SpecialClassController extends Controller
{

    public function fetch(Request $request)
    {
        $this->validate($request->all(), [
            "special_id" => "required",
        ]);
        $special_id = $request->get("special_id", null);
        $page = $request->get("page", 1);
        $size = $request->get("size", 20);
        $type = $request->get("type", false);

        $where = [["special_id", "=", $special_id]];
        if ($type !== false) {
            $where[] = ["type", "=", $type];
        }
        $count = SpecialClass::query()->where($where)->count();
        $pager = new Pager($page, $size);

        if ($count == 0) {
            return $this->response(["list" => [], "meta" => $pager->getPager($count)]);
        }

        $special = SpecialClass::query()->where($where)->offset($pager->getFirstIndex())->limit($size)->get();
        $user_level = CardOrder::getUserVipLevel();

        $result = [];
        $special->map(function (SpecialClass $item) use (&$result, $user_level){
            $data = [
                "id" => $item->id,
                "name" => $item->name,
                "total_time" => $item->total_time,
                "is_buy" => $user_level > 0 ? 0 : 1
            ];

            $result[] = $data;
        });

        return $this->response(["list" => $result, "meta" => $pager->getPager($count)]);
    }

    public function get(Request $request)
    {
        $this->validate($request->all(), [
            "special_class_id" => "required|Integer"
        ]);

        $special_class_id = $request->get("special_class_id");

        /** @var SpecialClass $special_class */
        $special_class = SpecialClass::find($special_class_id);

        if (!$special_class) {
            $this->response([], 900001, "课程不存在")->send();
        }

        if(SpecialClassOrder::checkUserIsBuy($special_class_id)){
            $special_class->setAttribute("is_buy", 0);
        }else{
            if(CardOrder::getUserVipLevel() > 0){
                $special_class->setAttribute("is_buy", 0);
            }else{
                $special_class->removeVideoUrl();
                $special_class->setAttribute("is_buy", 1);
            }
        }

        $special_class->teacher;

        return $this->response($special_class);
    }

    public function play(Request $request)
    {
        $this->validate($request->all(), [
            "special_class_id" => "required|Integer"
        ]);

        $special_class_id = $request->get("special_class_id");

        /** @var SpecialClass $special_class */
        $special_class = SpecialClass::find($special_class_id);

        if (!$special_class) {
            $this->response([], 900001, "课程不存在")->send();
        }

        $special_class->play_times ++;

        $special_class->save();

        $this->response([]);
    }
}
