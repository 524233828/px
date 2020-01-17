<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-01-05
 * Time: 17:44
 */

namespace App\Api\Controllers;


use App\Models\Category;
use Illuminate\Http\Request;
use JoseChan\Base\Api\Controllers\Controller;

/**
 * 课程分类相关
 * Class CategoryController
 * @package App\Api\Controllers
 */
class CategoryController extends Controller
{

    /**
     * 根据父类ID获取分类
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function fetchByParentId(Request $request)
    {
        $parent_id = $request->get("parent_id", 0);

        $category = Category::where("parent_id", "=", $parent_id)->get();
        if($category){
            return $this->response(["list" => $category->toArray()]);
        }
        return $this->response(["list" => []]);

    }
}