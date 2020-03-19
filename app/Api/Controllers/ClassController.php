<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-01-14
 * Time: 21:03
 */

namespace App\Api\Controllers;


use App\Collections\ClassCollection;
use App\Models\Category;
use App\Models\Classes;
use App\Models\ClassOrder;
use App\Models\Shop;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use JoseChan\Base\Api\Controllers\Controller;
use JoseChan\Pager\Pager;
use JoseChan\UserLogin\Constants\User;

/**
 * 课程
 * Class ClassController
 * @package App\Api\Controllers
 */
class ClassController extends Controller
{

    /**
     * 获取课程列表
     * 流程
     * 1：先根据分类获取所有分类下课程所属的店铺
     * 2：计算店铺距离，按距离排序
     * 3：根据排好序的店铺读取相关课程
     * 4：根据课程读取相关评价
     * @param Request $request
     * @return JsonResponse
     */
    public function fetch(Request $request)
    {
        $this->validate($request->all(), [
//            "category" => "required",
//            "latitude" => "required",
//            "longitude" => "required",
        ]);

        $category = $request->get("category", null);
        $latitude = $request->get("latitude", null);
        $longitude = $request->get("longitude", null);
        $keyword = $request->get("keyword", null);
        $page = $request->get("page", 1);
        $size = $request->get("size", 20);
        $type = $request->get("type", 1);

        $pager = new Pager($page, $size);

        $where = [];

        $class_builder = Classes::query();

        if (!empty($keyword)) {
            $where[] = ["name", "=", $keyword];
        }

        $where[] = ["type", "=", $type];

        $class_builder->where($where);

        if (!empty($category)) {
            /** @var Category|null $category_obj */
            $category_obj = Category::query()->find($category);

            if ($category_obj) {
                if ($category_obj->parent_id == 0) {
                    $children_category = $category_obj->getChildren();

                    if ($children_category->isNotEmpty()) {
                        $ids = array_column($children_category->toArray(), "id");

                        $class_builder->whereIn("category_id", $ids);
                    } else {
                        return $this->response(["list" => [], "meta" => $pager->getPager(0)]);
                    }
                } else {
                    $class_builder->where("category_id", "=", $category);
                }
            }
        }

        /** @var ClassCollection $classes 获取课程 */
        $classes = $class_builder->orderByDesc("id")->get();

        $classes->map(function (Classes $item, $key) {
            return $item->shop->computeCommentsInfo();
        });

        if (!empty($latitude) && !empty($longitude)) {
            $classes->sortBy(function (Classes $item, $key) use ($latitude, $longitude) {
                return $item->shop->computeCommentsInfo()->computeDistance($latitude, $longitude);
            });
        }

        if($type == 2){
            $classes->map(function (Classes $item, $key) {
                return $item->setAttribute("is_appoint", 0);
            });
        }else{
            $classes->map(function (Classes $item, $key) {
                return $item->setAttribute("is_appoint", 1);
            });
        }

        $classes->computeCommentsInfo();

        $classes->getAgeInfo();

        $count = $classes->count();

        $classes = $classes->slice($pager->getFirstIndex(), $size);

        return $this->response(["list" => $classes, "meta" => $pager->getPager($count)]);
    }

    /**
     * 获取课程详情
     * @param Request $request
     * @return JsonResponse
     */
    public function get(Request $request)
    {
        $this->validate($request->all(), [
            "class_id" => "required|Integer"
        ]);

        $class_id = $request->get("class_id");
        $latitude = $request->get("latitude", null);
        $longitude = $request->get("longitude", null);

        /** @var Classes $class */
        $class = Classes::find($class_id);

        //类型是线上课程，判断是否购买，购买过才返回视频
        if ($class->type == 2) {
//            $class->setAttribute("is_appoint", 0);
//            if (ClassOrder::checkUserIsBuy($class_id)) {
//                //购买过
                $class->setAttribute("is_buy", 0);
                $class->video;
//            } else {
//                $class->setAttribute("is_buy", 1);
//                $class->video;
//                $class->video->path = "";
//            }
        } else {
            $class->setAttribute("is_appoint", 1);
            $class->video;
        }

        $class->computeCommentsInfo();

        $class->shop->computeCommentsInfo();


        if (!empty($latitude) && !empty($longitude)) {
            $class->shop->computeDistance($latitude, $longitude);
        }

        $class->setAgeInfo();

        $class->isCollect(User::$info);
        return $this->response($class);
    }

    /**
     * 获取店铺的课程
     * @param Request $request
     * @return JsonResponse
     */
    public function fetchByShopId(Request $request)
    {
        $this->validate($request->all(), [
            "shop_id" => "required",
        ]);

        $shop_id = $request->get("shop_id");
        $page = $request->get("page", 1);
        $size = $request->get("size", 20);
        $type = $request->get("type", 1);

        $pager = new Pager($page, $size);

        $where[] = ["shop_id", "=", $shop_id];
        $where[] = ["type", "=", $type];

        $count = Classes::query()->where($where)->count();

        /** @var ClassCollection $classes 获取课程 */
        $classes = Classes::query()
            ->where($where)
            ->offset($pager->getFirstIndex())
            ->limit($size)
            ->get();

        $classes->computeCommentsInfo();

        $classes->getAgeInfo();

        $classes = $classes->slice($pager->getFirstIndex(), $size);

        return $this->response(["list" => $classes, "meta" => $pager->getPager($count)]);
    }

}