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

class BannerController extends Controller
{

    public function fetch()
    {
        $banners = Banner::fetchUseful();

        return $this->response(["list" => $banners->toArray()]);
    }

}