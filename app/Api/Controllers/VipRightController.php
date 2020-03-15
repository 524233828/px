<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-03-15
 * Time: 19:32
 */

namespace App\Api\Controllers;


use App\Models\VipRight;
use JoseChan\Base\Api\Controllers\Controller;

class VipRightController extends Controller
{

    public function fetch()
    {
        $index_entry = VipRight::query()->orderBy("sort", "desc")->get();

        return $this->response(["list" => $index_entry]);
    }

}
