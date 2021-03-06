<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-01-15
 * Time: 17:36
 */

namespace App\Collections;


use App\Models\Classes;
use App\Models\Shop;
use Illuminate\Database\Eloquent\Collection;

/**
 * 课程模型集合
 * Class ClassCollection
 * @package App\Collections
 */
class ClassCollection extends Collection
{

    /**
     * 根据课程获取所有店铺
     * @return \Illuminate\Database\Eloquent\Builder[]|ShopCollection
     */
    public function getAllShop()
    {
        $shop_ids = [];
        $this->map(function (Classes $item, $key) use (&$shop_ids) {
            if (!in_array($item->shop_id, $shop_ids)) {
                $shop_ids[] = $item->shop_id;
            }
        });

        return Shop::query()->whereIn("id", $shop_ids)->get();
    }

    /**
     * 获取所有评价
     */
    public function getComments()
    {
        return $this->map(function (Classes $item, $key) use (&$shop_ids) {
            $item->comments;
        });
    }

    /**
     * 计算评价信息
     * @return ClassCollection|\Illuminate\Support\Collection
     */
    public function computeCommentsInfo()
    {
        return $this->map(function (Classes $item, $key) use (&$shop_ids) {
            $item->computeCommentsInfo();
        });
    }

    public function getAgeInfo(){
        return $this->map(function (Classes $item, $key) use (&$shop_ids) {
            $item->setAgeInfo();
        });
    }


}