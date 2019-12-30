<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2019-12-03
 * Time: 18:08
 */

namespace JoseChan\Base\Sdk;


use GuzzleHttp\Client;
use JoseChan\Signature\SignAdaptor;

/**
 * Class BaseSdk
 * @package JoseChan\Base\Sdk
 */
class BaseSdk
{
    /**
     * @var Client $client
     */
    protected $client;

    /**
     * @var array $config 配置项
     */
    protected $config;

    public function __construct(Client $client, $config)
    {
        $this->client = $client;

        $this->config = $config;
    }

    /**
     * 获取配置项
     * @param $key
     * @param mixed $default
     * @param array $config
     * @return mixed|null
     */
    public function getConfig($key, $default = null, $config = [])
    {
        if (empty($config)) {
            $config = $this->config;
        }

        if (strpos($key, ".") !== false) {
            $keys = explode(".", $key);
            $first = array_shift($keys);

            if (isset($config[$first])) {
                return $this->getConfig(
                    implode(".", $keys),
                    $default,
                    $config[$first]
                );
            }

            return $default;
        }

        return isset($config[$key]) ? $config[$key] : $default;
    }

    /**
     * 获取所有配置
     * @return array
     */
    public function fetchConfig()
    {
        return $this->config;
    }

    /**
     * 设置配置项
     * @param $key
     * @param $value
     * @return $this
     */
    public function setConfig($key, $value)
    {
        $this->config[$key] = $value;

        return $this;
    }

    /**
     * 签名
     * @param $params
     * @param $sign_key
     * @return mixed
     */
    public function setSign($params, $sign_key = null)
    {
        $config = $this->getConfig("auth");

        if(empty($sign_key)){
            $sign_key = $config['private_key'];
        }

        $params['app_id'] = $config['app_id'];
        $params['sign_type'] = strtolower($config['sign_type']);

        ksort($params);

        $parameter_string = http_build_query($params);

        $params['sign'] = SignAdaptor::getInstance()->sign(
            $parameter_string,
            $sign_key,
            strtolower($config['sign_type'])
        );

        return $params;

    }
}
