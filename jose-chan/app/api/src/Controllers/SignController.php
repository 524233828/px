<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2019-12-16
 * Time: 17:39
 */

namespace JoseChan\App\Api\Controllers;


use Illuminate\Http\Request;
use JoseChan\App\Api\Logic\SignLogic;
use JoseChan\Base\Api\Controllers\Controller;

/**
 * 签名相关控制器
 * Class SignController
 * @package JoseChan\App\Api\Controllers
 */
class SignController extends Controller
{

    /**
     * 验签
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verify(Request $request)
    {
        $data = $request->query->all();

        $this->validate($data, [
            "app_id" => "required",
            "parameter_string" => "required|string",
            "sign" => "required|string",
            "sign_type" => "required|in:rsa,hash",
        ]);

        return $this->response(
            SignLogic::getInstance()->verify(
                $data['app_id'],
                urldecode($data['parameter_string']),
                $data['sign'],
                $data['sign_type']
            )
        );
    }

}