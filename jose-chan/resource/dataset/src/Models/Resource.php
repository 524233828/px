<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2019/1/1
 * Time: 11:11
 */

namespace JoseChan\Resourec\DataSet\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Redis;
use JoseChan\Resourec\DataSet\Collection\ResourceCollection;

/**
 * 应用模型
 * Class Resource
 * @package JoseChan\App\DataSet\Models
 */
class Resource extends Model
{

    //redis key
    const TOKEN_KEY = "app_token:";

    //token过期时间
    const EXPIRE_TIME = 7200;

    /**
     * 设置采用的集合类
     * @param array $models
     * @return \Illuminate\Database\Eloquent\Collection|ResourceCollection
     */
    public function newCollection(array $models = [])
    {
        return new ResourceCollection($models);
    }
}