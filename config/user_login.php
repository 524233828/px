<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2020-01-03
 * Time: 17:17
 */

return [
    "jwt" => [
        "key" => "px", //密钥
        "iss" => "http://www.px.com",
        "alg" => "HS256",
        "expired" => 365*86400, //过期时间
        "user_model" => \App\Models\PxUser::class, //用户模型
    ],

    //小程序配置
    "mini_program" => [
        "app_id" => env("MINI_PROGRAM_APP_ID", ""), //app_id
        "app_secret" => env("MINI_PROGRAM_APP_SECRET", ""), //app_secret
        "register_handler" => \App\Libraries\RegisterHandler::class //注册处理器
    ]
];