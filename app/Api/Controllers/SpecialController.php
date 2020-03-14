<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-03-14
 * Time: 10:53
 */

namespace App\Api\Controllers;


use App\Models\Special;
use Illuminate\Http\Request;
use JoseChan\Base\Api\Controllers\Controller;
use JoseChan\Pager\Pager;

class SpecialController extends Controller
{

    public function fetch(Request $request){
        $this->validate($request->all(), [
            "teacher_id" => "required"
        ]);

        $teacher_id = $request->get("teacher_id");
        $page = $request->get("page", 1);
        $size = $request->get("size", 20);

        $where = [["teacher_id", "=", $teacher_id]];
        $count = Special::query()->where($where)->count();
        $pager = new Pager($page, $size);

        if($count == 0){
            return $this->response(["list"=>[], "meta" => $pager->getPager($count)]);
        }

        $special = Special::query()->where($where)->offset($pager->getFirstIndex())->limit($size)->get();

//        $comment->map(function ($item){
//            $item->user;
//        });

        return $this->response(["list"=>$special, "meta" => $pager->getPager($count)]);
    }

    public function get(Request $request){
        $this->validate($request->all(), [
            "special_id" => "required|Integer"
        ]);

        $special_id = $request->get("special_id");

        /** @var Special $special_id */
        $special = Special::find($special_id);

        if(!$special){
            $this->response([], 900001, "专栏不存在")->send();
        }

        return $this->response($special);
    }
}
