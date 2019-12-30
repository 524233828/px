<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2019-12-07
 * Time: 16:08
 */

namespace JoseChan\Signature;


use phpseclib\Crypt\Hash;

/**
 * 安全哈希签名算法
 * 使用的sha1算法，其他sha系列怕影响效率
 * Class ShaHashSign
 * @package JoseChan\Signature
 */
class ShaHashSign
{
    /**
     * @var Hash $engine
     */
    private $engine;

    public function __construct()
    {
        $this->engine = new Hash("sha1");
    }

    /**
     * 签名
     * @param $message
     * @param $key
     * @return string
     */
    public function sign($message, $key)
    {
        $this->engine->setKey($key);
        return $this->engine->hash($message);
    }

    /**
     * 验签
     * @param $message
     * @param $sign
     * @param $key
     * @return bool
     */
    public function verify($message, $sign, $key)
    {
        $this->engine->setKey($key);

        return $this->engine->hash($message) === $sign;
    }
}