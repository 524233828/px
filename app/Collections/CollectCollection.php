<?php
/**
 * Created by PhpStorm.
 * User: lao
 * Date: 2020/1/22
 * Time: 17:41
 */


namespace App\Collections;


use App\Models\Collect;
use Illuminate\Database\Eloquent\Collection;

/**
 * 收藏模型集合
 * Class CollectCollection
 * @package App\Collections
 */
class CollectCollection extends Collection
{
    /**
     * 获取所有店铺
     */
    public function getShops()
    {
        return $this->map(function (Collect $item, $key) {
            $item->shop;
        });
    }

    /**
     * 获取所有课程
     */
    public function getClasses()
    {
        return $this->map(function (Collect $item, $key){
            $item->classes;
        });
    }
}