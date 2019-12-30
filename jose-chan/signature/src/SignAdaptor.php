<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2019-12-07
 * Time: 16:27
 */

namespace JoseChan\Signature;


use JoseChan\RsaSign\Signature;

/**
 * 签名算法适配器，适配了hash算法签名及私钥签名两种
 * Class SignAdaptor
 * @package JoseChan\Signature
 */
class SignAdaptor
{

    const HASH = "hash";
    const RSA = "rsa";

    /**
     * @var array $sign_type 签名算法类
     */
    private static $sign_type = [
        self::HASH => ShaHashSign::class,
        self::RSA  => Signature::class
    ];

    /**
     * @var array $signature 签名算法对象
     */
    private static $signature = [];

    /**
     * @var self $instance 适配器实例
     */
    private static $instance;

    /**
     * 获取实例
     * @return SignAdaptor
     */
    public static function getInstance()
    {
        if(self::$instance instanceof self)
        {
            return self::$instance;
        }

        return self::$instance = new self();
    }

    /**
     * 签名
     * @param string $message 签名字符串
     * @param string $key 签名key
     * @param string $type 签名类型
     * @return string
     */
    public function sign($message, $key, $type = SignAdaptor::HASH)
    {

        $signature = self::getSignature($type);

        return urlencode($signature->sign($message, $key));
    }

    /**
     * 校验签名
     * @param string $message 签名字符串
     * @param string $sign 签名
     * @param string $key 签名key
     * @param string $type 类型
     * @return bool
     */
    public function verify($message, $sign, $key, $type = SignAdaptor::HASH){

        $signature = self::getSignature($type);

        return $signature->verify($message, urldecode($sign), $key);
    }

    /**
     * 获取签名算法实体类
     * @param string $type
     * @return Signature|ShaHashSign
     */
    private static function getSignature($type = SignAdaptor::HASH)
    {
        if(!isset(self::$signature[$type])){

            $class = self::$sign_type[$type];

            self::$signature[$type] = new $class;
        }

        return self::$signature[$type];
    }
}