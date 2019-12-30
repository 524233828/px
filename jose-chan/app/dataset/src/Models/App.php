<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2019/1/1
 * Time: 11:11
 */

namespace JoseChan\App\DataSet\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Redis;
use JoseChan\App\DataSet\Collection\AppCollection;

/**
 * 应用模型
 * Class App
 * @package JoseChan\App\DataSet\Models
 * @property int $id
 * @property string $name
 * @property string $app_secret
 * @property string $created_at
 * @property string $updated_at
 * @property string $private_key
 * @property int $status
 */
class App extends Model
{

    //redis key
    const TOKEN_KEY = "app_token:";

    //token过期时间
    const EXPIRE_TIME = 7200;

    /**
     * 设置采用的集合类
     * @param array $models
     * @return \Illuminate\Database\Eloquent\Collection|AppCollection
     */
    public function newCollection(array $models = [])
    {
        return new AppCollection($models);
    }

    /**
     * 生成当前应用的token
     * @return bool|string
     */
    public function initToken()
    {
        if($this->exists)
        {
            $key = $this->getTokenKey();
            $token = md5($this->id.$this->app_secret.time());
            //设置token
            Redis::set($key, $token);
            Redis::expire($key, self::EXPIRE_TIME);
            return $token;
        }else{
            return false;
        }
    }

    /**
     * 获取应用当前的token
     * @return mixed
     */
    public function getToken()
    {
        if($this->exists){
            return Redis::get($this->getTokenKey());
        }
    }

    /**
     * 获取redis key
     * @return string
     */
    public function getTokenKey()
    {
        if($this->exists){
            return self::TOKEN_KEY. $this->id;
        }
    }
}