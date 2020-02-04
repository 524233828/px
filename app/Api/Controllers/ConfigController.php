<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-02-04
 * Time: 14:39
 */

namespace App\Api\Controllers;


use App\Models\Config;
use Illuminate\Http\Request;
use JoseChan\Base\Api\Controllers\Controller;

class ConfigController extends Controller
{

    public function get(Request $request)
    {
        $this->validate($request->all(), ["key" => "required"]);

        $key = $request->get("key");

        $value = Config::get($key);

        if(!$value){
            return $this->response([], 8001, "配置项不存在");
        }

        return $this->response(["value" => $value]);
    }
}