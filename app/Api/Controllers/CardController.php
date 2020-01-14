<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-01-14
 * Time: 16:04
 */

namespace App\Api\Controllers;


use App\Models\CardOrder;
use App\Models\Classes;
use Illuminate\Http\Request;
use JoseChan\Base\Api\Controllers\Controller;
use JoseChan\UserLogin\Constants\User;

class CardController extends Controller
{

    public function fetch(Request $request)
    {
//        $this->validate($request->all(), [
//            "class_id" => "required"
//        ]);
//
//        $class_id = $request->get("class_id");
//
//        $classes = Classes::find($class_id);
//
//        if(!$classes){
//            return $this->response([],3000, "课程不存在");
//        }

        $card = CardOrder::query()
            ->where([
                ["user_id", "=", User::$info['id']],
                ["status", "=", 1],
            ])->get();

        if(!$card)
        {
            $card = [];
        }

        return $this->response(["list" => $card->toArray()]);
    }

}