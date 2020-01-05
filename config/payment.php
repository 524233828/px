<?php

return [
    "wechat_mina" => [
        'app_id'     => env('WECHAT_MINI_PROGRAM_APPID'), // 应用ID
        'app_secret' => env('WECHAT_MINI_PROGRAM_SECRET'), // 应用密钥
        'mch_id'     => '', // 微信支付商户号
        'mch_secret' => '', // 微信支付密钥
        "notify_url" => env("APP_URL")."/api/order/notify"
    ],
];