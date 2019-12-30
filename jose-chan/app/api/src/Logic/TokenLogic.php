<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2019-09-07
 * Time: 11:13
 */

namespace JoseChan\App\Api\Logic;


use Illuminate\Support\Facades\Redis;
use JoseChan\App\DataSet\Models\App;
use JoseChan\Base\Api\Logic\Logic;

/**
 * token相关逻辑层
 * Class TokenLogic
 * @package JoseChan\App\Api\Logic
 */
class TokenLogic extends Logic
{

    /**
     * 获取token
     * @param $app_id
     * @param $app_secret
     * @param bool $refresh
     * @return array
     * @throws \Exception
     */
    public function get($app_id, $app_secret, $refresh = true)
    {

        $app_model = new App();

        /**@var App|\Illuminate\Database\Eloquent\Collection|static[]|static|null $app */
        $app = $app_model->find($app_id);

        if(!$app || !$app->exists)
        {
            throw new \Exception("应用不存在",1000);
        }
        
        if($app->app_secret != $app_secret){
            throw new \Exception("应用密钥不正确",1001);
        }

        if(!$refresh)
        {
            return ["token" => $app->getToken()];
        }

        $token =  $app->initToken();

        return ["token" => $token];
    }

}