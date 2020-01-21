<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-01-14
 * Time: 21:03
 */

namespace App\Api\Controllers;


use App\Collections\ClassCollection;
use App\Models\Classes;
use App\Models\Shop;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use JoseChan\Base\Api\Controllers\Controller;
use JoseChan\Pager\Pager;

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
            "category" => "required",
            "latitude" => "required",
            "longitude" => "required",
        ]);

        $category = $request->get("category");
        $latitude = $request->get("latitude");
        $longitude = $request->get("longitude");
        $keyword = $request->get("keyword", null);
        $page = $request->get("page", 1);
        $size = $request->get("size", 20);

        $pager = new Pager($page, $size);

        $where = [
            ["category_id", "=", $category],
        ];

        if(!empty($keyword)){
            $where[] = ["name", "=", $keyword];
        }

        /** @var ClassCollection $classes 获取课程 */
        $classes = Classes::query()->where($where)->get();

        $classes->sortBy(function (Classes $item, $key) use ($latitude, $longitude) {
            return $item->shop->computeDistance($latitude, $longitude);
        });

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

        $class->computeCommentsInfo();

        $class->shop;

        $class->video;

        if (!empty($latitude) && !empty($longitude)) {
            $class->shop->computeDistance($latitude, $longitude);
        }

        $class->setAgeInfo();
        return $this->response($class);
    }

}