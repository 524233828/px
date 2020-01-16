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
use App\Models\Comment;
use Illuminate\Http\Request;
use JoseChan\Base\Api\Controllers\Controller;
use JoseChan\UserLogin\Constants\User;

class CommentController extends Controller
{
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

        $class = Classes::find($class_id);

        if (!$class) {
            return $this->response([], 4000, "课程不存在");
        }

        $appoint = Appoint::query()->where([
            ["class_id", "=", $class_id],
            ["uid", "=", User::$info['id']],
        ]);

        if(!$appoint){
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

        if($comment->save()){
            return $this->response([]);
        }else{
            return $this->response([], 4002, "评价失败");
        }
    }
}