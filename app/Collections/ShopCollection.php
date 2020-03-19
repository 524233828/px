<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-01-15
 * Time: 17:46
 */

namespace App\Collections;


use App\Models\Shop;
use Illuminate\Database\Eloquent\Collection;

/**
 * 商店模型的集合
 * Class ShopCollection
 * @package App\Collections
 */
class ShopCollection extends Collection
{
    /**
     * 计算距离
     * @param $latitude
     * @param $longitude
     * @return ShopCollection|\Illuminate\Support\Collection
     */
    public function computeDistance($latitude, $longitude)
    {
        return $this->map(function (Shop $item, $key) use ($latitude, $longitude) {
            $item->computeDistance($latitude, $longitude);
        });
    }

    /**
     * 按距离排序
     * @param $latitude
     * @param $longitude
     * @return ShopCollection
     */
    public function sortByDistance($latitude, $longitude)
    {
        return $this->sortBy(function (Shop $item, $key) use ($latitude, $longitude) {
            return $item->computeDistance($latitude, $longitude);
        });
    }

    /**
     * 综合排序
     * @param $latitude
     * @param $longitude
     * @return ShopCollection
     */
    public function sortSynthetic($latitude = null, $longitude = null)
    {
        return $this->sortByDesc(function (Shop $item, $key) use ($latitude, $longitude) {
            //综合排序权重目前分数最大值5分，距离最大值默认10000m
            //权重暂时用（分数*2000-距离）计算，越大权重越高
            if(!empty($latitude) && !empty($longitude)){
                $distance = $item->computeDistance($latitude, $longitude);
                return $item->computeCommentsInfo()->comment_star * 2000 - $distance;
            }

            return $item->computeCommentsInfo()->comment_star;
        });
    }

    /**
     * 按评分排序
     * @return ShopCollection
     */
    public function sortByStar()
    {
        return $this->sortByDesc(function (Shop $item, $key) {
            return $item->computeCommentsInfo()->comment_star;
        });
    }

    /**
     * 按线上课排序排序
     * @return ShopCollection
     */
    public function sortByOnline()
    {
        return $this->sortByDesc(function (Shop $item, $key) {
            $online_class = $item->classes->where("type", "=", 2);
            if($online_class->isEmpty()){
                return -1;
            }

            return $online_class->max("id");
        });
    }

    /**
     * 排序
     * @param $sort_type
     * @param null $latitude
     * @param null $longitude
     * @return $this
     * @throws \Exception
     */
    public function sortShops($sort_type, $latitude = null, $longitude = null)
    {
        if(!in_array($sort_type, Shop::$sort_type)){
            throw new \Exception("sort type invalid");
        }

        if (in_array($sort_type, [Shop::SORT_DISTANCE])) {
            if (empty($latitude) || empty($longitude)) {
                throw new \Exception("按距离排序需要当前经纬度！");
            }
        }

        //距离排序
        if($sort_type == Shop::SORT_DISTANCE){
            return $this->sortByDistance($latitude, $longitude);
        }

        //综合排序
        if($sort_type == Shop::SORT_SYNTHETIC){
            return $this->sortSynthetic($latitude, $longitude);
        }

        //评分排序
        if($sort_type == Shop::SORT_STAR){
           return $this->sortByStar();
        }

        if($sort_type == Shop::SORT_ONLINE)
        {
            return $this->sortByOnline();
        }

        if($sort_type == Shop::SORT_NOT) {
            //不排序的是首页
            $this->map(function (Shop $shop, $key) {
                if ($shop->classes->count() < 3) {
                    $this->offsetUnset($key);
                }
            });

            return $this->values();
        }

        return $this;
    }

    /**
     * 获取课程
     * @return ShopCollection
     */
    public function getClasses()
    {
        return $this->sortBy(function (Shop $item, $key) {
            return $item->classes->sortByDesc("id");
        });
    }

    /**
     * 计算评分
     * @return ShopCollection|\Illuminate\Support\Collection
     */
    public function computeCommentsInfo()
    {
        return $this->map(function (Shop $item, $key) {
            $item->computeCommentsInfo();
        });
    }

    public function unsetWithoutOnline()
    {
        $this->map(function (Shop $shop, $key) {
            if ($shop->classes->where("type", "=", 2)->isEmpty()) {
                $this->offsetUnset($key);
            }
        });

        return $this->values();
    }
}