<?php

return [
    "wechat_mina" => [
        'app_id'     => env('WECHAT_MINI_PROGRAM_APPID'), // 应用ID
        'app_secret' => env('WECHAT_MINI_PROGRAM_SECRET'), // 应用密钥
        'mch_id'     => env('WECHAT_PAY_MCH_ID'), // 微信支付商户号
        'mch_secret' => env('WECHAT_PAY_MCH_SECRET'), // 微信支付密钥
        "notify_url" => env("APP_URL")."/api/order/notify",
        "cert" => file_get_contents(env("WECHAT_PAY_MCH_CERT")),//支付证书
        "ssl_key" => file_get_contents(env("WECHAT_PAY_MCH_KEY")),//支付证书密钥
    ],
];