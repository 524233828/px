<?php
/**
 * Created by PhpStorm.
 * User: lao
 * Date: 2020/1/23
 * Time: 19:05
 */

namespace App\Api\Controllers;

use App\Models\Appoint;
use App\Models\Goods;
use function foo\func;
use Illuminate\Http\Request;
use JoseChan\Base\Api\Controllers\Controller;
use JoseChan\Pager\Pager;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\MongoDbSessionHandler;

/**
 * 商品相关
 * Class GoodsController
 * @package App\Api\Controllers
 */
class GoodsController extends Controller
{
    public function fetch(Request $request)
    {
        $page = $request->get("page", 1);
        $size = $request->get("size", 20);

        $pager = new Pager($page, $size);

        $count = Goods::query()->count();

        $goods = Goods::query()
            ->offset($pager->getFirstIndex())
            ->limit($size)
            ->get();

        if($goods->isEmpty()){
            return $this->response(["list" => [], "meta" => $pager->getPager(0)]);
        }

        $goods = $goods->map(function (Goods $item, $key){
            $item->computeBuyNum();
        });

        return $this->response(["list" => $goods, "meta" => $pager->getPager($count)]);

    }

    public function get(Request $request)
    {
        $this->validate($request->all(), [
            "goods_id" => "required|Integer"
        ]);

        $goods_id = $request->get("goods_id");

        /** @var Goods $goods */
        $goods = Goods::find($goods_id);

        if(!$goods){
            return $this->response([], 5001, "商品不存在");
        }

        $goods = $goods->computeBuyNum();

        return $this->response($goods);

    }
}