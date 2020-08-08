<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-01-16
 * Time: 10:41
 */

namespace App\Api\Controllers;


use App\Collections\ShopCollection;
use App\Models\Config;
use App\Models\Shop;
use Illuminate\Http\Request;
use JoseChan\Base\Api\Controllers\Controller;
use JoseChan\Pager\Pager;
use JoseChan\UserLogin\Constants\User;

/**
 * 店铺相关
 * Class ShopController
 * @package App\Api\Controllers
 */
class ShopController extends Controller
{

    /**
     * 获取店铺列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function fetch(Request $request)
    {
        $latitude = $request->get("latitude", null);
        $longitude = $request->get("longitude", null);
        $keyword = $request->get("keyword", null);
        $sort = $request->get("sort", Shop::SORT_NOT);
        $page = $request->get("page", 1);
        $size = $request->get("size", 20);

        $district_id = $request->get("area_code", null);

        $pager = new Pager($page, $size);
        $where = [];

        if (!empty($keyword)) {
            $where[] = ["name", "LIKE", "%$keyword%"];
        }

        if(!empty($district_id)){
            $where[] = ["district_id", "=", $district_id];
        }

        /** @var ShopCollection $shops */
        $shops = Shop::query()->where($where)->orderByDesc("sort")->get();

        try {
            $shops = $shops->sortShops($sort, $latitude, $longitude);

            $shops = $shops->values();

            $shops->getClasses()->computeCommentsInfo();
        } catch (\Exception $exception) {
            return $this->response([], 5000, $exception->getMessage());
        }

        if (!empty($latitude) && !empty($longitude)) {
            $shops->computeDistance($latitude, $longitude);
        }

        if($sort == Shop::SORT_ONLINE){
            $shops->unsetWithoutOnline();
        }

        $count = $shops->count();

        $shops = $shops->slice($pager->getFirstIndex(), $size);

        return $this->response(["list" => $shops->values(), "meta" => $pager->getPager($count)]);
    }

    /**
     * 店铺详情
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function get(Request $request)
    {
        $this->validate($request->all(), [
            "shop_id" => "required|Integer"
        ]);

        $shop_id = $request->get("shop_id");
        $latitude = $request->get("latitude", null);
        $longitude = $request->get("longitude", null);

        /** @var Shop $shop */
        $shop = Shop::find($shop_id);

        if(!$shop){
            return $this->response([], 5001, "店铺不存在");
        }

        if (!empty($latitude) && !empty($longitude)) {
            $shop->computeDistance($latitude, $longitude);
        }

//        $shop->classes;

        if(!Config::get("review_mode")){
            $shop->video;
        }

        $shop->computeCommentsInfo();

        $shop->isCollect(User::$info);

        $shop->isLike(User::$info);

        return $this->response($shop);
    }
}