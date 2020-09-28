<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-01-14
 * Time: 16:04
 */

namespace App\Api\Controllers;

use App\Models\Card;
use App\Models\CardOrder;
use App\Models\CardOrderChild;
use App\Models\Classes;
use Illuminate\Http\Request;
use JoseChan\Base\Api\Controllers\Controller;
use JoseChan\UserLogin\Constants\User;
use Illuminate\Database\Eloquent\Collection;

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

        $card = CardOrder::query()
            ->where([
                ["user_id", "=", User::$info['id']],
                ["status", "=", 1],
                ["expired_time", ">", time()],
            ])->get();

        $child_models = [];

        $card->map(function (CardOrder $item) use (&$child_models){
            $item->cardOrderChild->map(function (CardOrderChild $item) use (&$child_models){
                $item->setAge();
                $child_models[] = $item;
            });
        });


        if(!$child_models)
        {
            $card = [];
        }

        return $this->response(["list" => $card]);
    }

    public function info(Request $request)
    {
        $this->validate($request->all(), [
            "id" => "required"
        ]);

        $id = $request->get("id");

        /** @var Card $card */
        $card = Card::query()->find($id);

        if(!$card){
            return $this->response([],9000, "卡券不存在");
        }

        $card->expired_date;

        return $this->response($card->toArray());
    }

    public function listCard()
    {

        /** @var Collection $card */
        $card = Card::query()->get();

        return $this->response(["list" => $card->toArray()]);
    }

}