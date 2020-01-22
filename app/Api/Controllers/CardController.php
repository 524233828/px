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

/**
 * 卡券相关
 * Class CardController
 * @package App\Api\Controllers
 */
class CardController extends Controller
{

    /**
     * 获取所有卡券
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
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

        $card->map(function (CardOrder $item){
           $item->setAge();
        });

        if(!$card)
        {
            $card = [];
        }else{
            $card = $card->toArray();
        }

        return $this->response(["list" => $card]);
    }

}