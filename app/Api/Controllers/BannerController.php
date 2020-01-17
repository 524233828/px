<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-01-04
 * Time: 13:19
 */

namespace App\Api\Controllers;

use App\Models\Banner;
use JoseChan\Base\Api\Controllers\Controller;

/**
 * banner相关
 * Class BannerController
 * @package App\Api\Controllers
 */
class BannerController extends Controller
{

    /**
     * 获取banner列表
     * @return \Illuminate\Http\JsonResponse
     */
    public function fetch()
    {
        $banners = Banner::fetchUseful();

        return $this->response(["list" => $banners->toArray()]);
    }

}