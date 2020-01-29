<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-01-16
 * Time: 10:41
 */

namespace App\Api\Controllers;


use App\Collections\ShopCollection;
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
            $where[] = ["name", "=", $keyword];
        }

        if(!empty($district_id)){
            $where[] = ["district_id", "=", $district_id];
        }

        /** @var ShopCollection $shops */
        $shops = Shop::query()->where($where)->get();

        try {
            $shops = $shops->sortShops($sort, $latitude, $longitude);

            $shops->getClasses()->computeCommentsInfo();
        } catch (\Exception $exception) {
            return $this->response([], 5000, $exception->getMessage());
        }

        if (!empty($latitude) && !empty($longitude)) {
            $shops = $shops->computeDistance($latitude, $longitude);
        }

        $count = $shops->count();

        $shops = $shops->slice($pager->getFirstIndex(), $size);

        return $this->response(["list" => $shops, "meta" => $pager->getPager($count)]);
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

        $shop->video;

        $shop->computeCommentsInfo();

        $shop->isCollect(User::$info);

        return $this->response($shop);
    }
}