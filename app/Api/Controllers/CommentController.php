<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-01-14
 * Time: 19:33
 */

namespace App\Api\Controllers;


use App\Models\Appoint;
use App\Models\Classes;
use App\Models\ClassOrder;
use App\Models\Comment;
use Illuminate\Http\Request;
use JoseChan\Base\Api\Controllers\Controller;
use JoseChan\Pager\Pager;
use JoseChan\UserLogin\Constants\User;

/**
 * 评价相关
 * Class CommentController
 * @package App\Api\Controllers
 */
class CommentController extends Controller
{
    /**
     * 评价课程
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $this->validate($request->all(), [
            "class_id" => "required|Integer",
            "star" => "required|Integer|in:1,2,3,4,5",
            "comment" => "required"
        ]);

        $class_id = $request->get("class_id");
        $star = $request->get("star");
        $comment = $request->get("comment");

        /** @var Classes $class */
        $class = Classes::find($class_id);

        if (!$class) {
            return $this->response([], 4000, "课程不存在");
        }

        $appoint = Appoint::query()->where([
            ["class_id", "=", $class_id],
            ["uid", "=", User::$info['id']],
        ]);

        $class_order = ClassOrder::query()->where([
            ["class_id", "=", $class_id],
            ["user_id", "=", User::$info['id']],
            ["status", "=", 2],
        ]);

        if (!$appoint && !$class_order) {
            return $this->response([], 4001, "您未购买或预约过该课程");
        }

        $comment_data = [
            "uid" => User::$info['id'],
            "class_id" => $class_id,
            "shop_id" => $class->shop_id,
            "comment" => $comment,
            "star" => $star
        ];

        $comment = new Comment($comment_data);

        if ($comment->save()) {
            return $this->response([]);
        } else {
            return $this->response([], 4002, "评价失败");
        }
    }

    public function fetch(Request $request)
    {
        $this->validate($request->all(), [
            "type" => "required|in:1,2",
            "id" => "required"
        ]);

        $type = $request->get("type");
        $id = $request->get("id");
        $page = $request->get("page", 1);
        $size = $request->get("size", 20);

        $where = [];
        if($type == 1){
            $where[] = ["shop_id", "=", $id];
        }else{
            $where[] = ["class_id", "=", $id];
        }

        $count = Comment::query()->where($where)->count();
        $pager = new Pager($page, $size);

        if($count == 0){
            return $this->response(["list"=>[], "meta" => $pager->getPager($count)]);
        }

        $comment = Comment::query()->where($where)->offset($pager->getFirstIndex())->limit($size)->get();

        $comment->map(function ($item){
            $item->user;
        });

        return $this->response(["list"=>$comment, "meta" => $pager->getPager($count)]);
    }
}