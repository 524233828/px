## 签名适配器

#### 安装

````
composer require "jose-chan/signature"
````

#### 示例

````php

require "vendor/autoload.php";

use JoseChan\Signature\SignAdaptor;

//签名
//hash签名
SignAdaptor::getInstance()->sign(
    $parameter_string,//待签名字符串
    $sign_key,//用于签名的key
    SignAdaptor::HASH
);

//RSA签名
SignAdaptor::getInstance()->sign(
    $parameter_string,//待签名字符串
    $private_key,//私钥
    SignAdaptor::RSA
);

//验签
//hash验签
SignAdaptor::getInstance()->verify(
    $parameter_string,//待签名字符串
    $sign,//签名
    $sign_key,//用于签名的key
    SignAdaptor::HASH
);

//RSA验签
SignAdaptor::getInstance()->sign(
    $parameter_string,//待签名字符串
    $sign,//签名
    $public_key,//公钥
    SignAdaptor::RSA
);


````