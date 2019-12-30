<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2019-09-04
 * Time: 18:31
 */


namespace JoseChan\Base\Api\Controllers;

/**
 * api控制器基类
 * Class Controller
 * @package JoseChan\Base\Api\Controllers
 */
class Controller
{

    /**
     * 参数检查
     * @param $data
     * @param $rule
     */
    protected function validate($data, $rule)
    {
        $validator = validator($data, $rule);

        if($validator->fails())
        {
            $err = $validator->errors()->toArray();

            $err_msg = "";
            foreach ($err as $field => $errors)
            {
                $err_msg .= "{$field}: " . implode(", ", $errors)." ";
            }

            $this->response([], 10001, "参数不正确：{$err_msg}")->send();
        }
    }

    /**
     * 响应结果
     * @param $data
     * @param int $code
     * @param string $msg
     * @param int $status
     * @param array $header
     * @return \Illuminate\Http\JsonResponse
     */
    protected function response($data, $code = 1, $msg = 'success', $status = 200, array $header = [])
    {
        $result['data'] = !is_array($data) && !is_null(json_decode($data)) ? json_decode($data, true) : $data;
        $result['msg'] = $msg;
        $result['code'] = $code;

        return \response()->json($result, $status, $header);
    }
}