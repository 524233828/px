<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-01-10
 * Time: 16:48
 */

namespace App\Api\Controllers;


use App\Models\Child;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use JoseChan\Base\Api\Controllers\Controller;
use JoseChan\UserLogin\Constants\User;

/**
 * 常用人相关
 * Class ChildController
 * @package App\Api\Controllers
 */
class ChildController extends Controller
{

    /**
     * 新增/编辑常用人
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function save(Request $request)
    {
        $this->validate($request->all(), [
            "name" => "required",
            "tel" => "required",
            "birth" => "required|date",
            "gender" => "required|in:1,2",
        ]);

        $id = $request->get("id", false);
        $name = $request->get("name");
        $tel = $request->get("tel");
        $birth = $request->get("birth");
        $gender = $request->get("gender");

        if ($id) {
            $child = Child::find($id);
            if ($child->uid != User::$info['id']) {
                return $this->response([], 2000, "无权修改别人的常用人信息");
            }
        } else {
            $child = new Child(["uid" => User::$info['id']]);
        }

        $child->name = $name;
        $child->tel = $tel;
        $child->birth = $birth;
        $child->gender = $gender;

        if ($child->save()) {
            return $this->response([]);
        } else {
            return $this->response([], 2001, "保存失败");
        }
    }

    /**
     * 获取常用人列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function fetch(Request $request)
    {
        /** @var Collection $children */
        $children = Child::where("uid", "=", User::$info['id'])->get();

        if ($children->isNotEmpty()) {
            return $this->response(["list" => $children->toArray()]);
        }

        return $this->response(["list" => []]);
    }

    /**
     * 获取常用人信息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function get(Request $request)
    {

        $this->validate($request->all(), [
            "id" => "required",
        ]);

        $id = $request->get("id");

        /** @var Child $child */
        $child = Child::find($id);

        if (!$child) {
            return $this->response([], 2002, "常用人不存在");
        }

        return $this->response($child->toArray());
    }

    public function delete(Request $request)
    {
        $this->validate($request->all(), [
            "id" => "required",
        ]);

        $id = $request->get("id");

        /** @var Child $child */
        $child = Child::find($id);

        if (!$child) {
            return $this->response([], 2002, "常用人不存在");
        }

        if ($child->uid != User::$info['id']) {
            return $this->response([], 2000, "无权删除别人的常用人信息");
        }

        if($child->delete()){
            return $this->response([]);
        }

        return $this->response([], 2003, "删除失败");
    }
}