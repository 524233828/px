<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-01-21
 * Time: 16:12
 */

namespace App\Api\Controllers;


use App\Models\ChinaArea;
use Illuminate\Http\Request;
use JoseChan\Base\Api\Controllers\Controller;

class AreaController extends Controller
{

    public function getAreaByParentArea(Request $request)
    {
        $parent_area = $request->get("parent_area", 440200);

        /** @var ChinaArea $area */
        $area = ChinaArea::query()->where("code", "=", $parent_area)->first();

        if(!$area){
            return $this->response([], 8000, "地区不存在");
        }

        $children = $area->getChildren();

        if($children->isEmpty()){
            $children = [];
        }

        return $this->response(["list" => $children]);
    }

}