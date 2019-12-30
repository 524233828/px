<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2019-09-07
 * Time: 14:58
 */

namespace JoseChan\App\Api\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider;

/**
 * 应用服务提供者
 * Class AppServiceProvider
 * @package JoseChan\App\Api\Providers
 */
class AppServiceProvider extends RouteServiceProvider
{
    /** 定义命名空间 **/
    protected $namespace = "JoseChan\App\Api\Controllers";

    /**
     * 初始化
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * 路由配置
     */
    public function map()
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(__DIR__ . "/../../routes/routes.php");
    }

}