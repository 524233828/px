<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-01-05
 * Time: 17:44
 */

namespace App\Api\Controllers;


use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
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

        $category = Category::query()
            ->where("parent_id", "=", $parent_id)
            ->orderByDesc("sort")
            ->get();
        if($category){
            return $this->response(["list" => $category->toArray()]);
        }
        return $this->response(["list" => []]);
    }

    public function fetch(){
        /** @var Collection $category */
        $category = Category::query()->where("status", "=", 1)->get();

        $parent_list = [];
        $child_list = [];
        if($category->isNotEmpty()){
            foreach ($category->toArray() as $item){
                if($item['parent_id'] == 0){
                    $parent_list[$item['id']] = $item;
                }else{
                    $child_list[$item['parent_id']][] = $item;
                }
            }

            if(!empty($child_list)){
                foreach ($child_list as $parent_id => $children){
                    if(isset($parent_list[$parent_id])){
                        $parent_list[$parent_id]['children'] = $children;
                    }
                }
            }
        }

        return $this->response(["list" => $parent_list]);
    }
}