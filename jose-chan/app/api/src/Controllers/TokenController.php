<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2019-09-04
 * Time: 18:28
 */

namespace JoseChan\App\Api\Controllers;


use Illuminate\Support\Facades\DB;
use JoseChan\App\Api\Logic\TokenLogic;
use JoseChan\Base\Api\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * token相关控制器
 * Class TokenController
 * @package JoseChan\App\Api\Controllers
 */
class TokenController extends Controller
{

    /**
     * 获取token
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function getToken(Request $request)
    {
        $data = $request->query->all();

        $this->validate($data,[
            "app_id" => "required",
            "app_secret" => "required|string"
        ]);
        
        return $this->response(TokenLogic::getInstance()->get($data['app_id'], $data['app_secret']));
    }
}