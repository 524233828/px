<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-04-07
 * Time: 12:49
 */

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use JoseChan\UserLogin\Constants\User;

class PhoneCheck
{
    public function handle(Request $request, \Closure $next, $gurad = null)
    {
        if (!empty(User::$info) && !empty(User::$info->phone_number)) {
            $response = $next($request);
        } else {
            return \response()->json([
                "code" => 10002,
                "msg" => "未授权获取用户手机号",
                "data" => []
            ]);
        }

        return $response;
    }
}
