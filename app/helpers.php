<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2019/1/7
 * Time: 11:10
 */


/**
 * 获取日志对象
 * @param string $filename 文件名
 * @param int $level 日志等级
 * @return \Monolog\Logger monolog对象
 */
function myLog($filename = "debug", $level = \Monolog\Logger::DEBUG)
{
    //实例化一个Logger对象
    $log = new \Monolog\Logger($filename);
    $log_path = base_path("/runtime/logs/");

    //日志文件保留天数，0为保留所有，n为保留最近n天的日志，可配置
    $max_file = config()->get("log_limit", 0);
    //是否记录日志，可配置，true为记录，false为不记录
    $is_log = config()->get("is_log", true);
    $log->pushHandler(
        (new \Monolog\Handler\RotatingFileHandler($log_path.$filename, $max_file, $level))
            ->setFormatter(
                new \Monolog\Formatter\LineFormatter(
                    \Monolog\Formatter\LineFormatter::SIMPLE_FORMAT,
                    "H:i:s.u"
                )
            )//记录毫秒数
    );

    if (!$is_log) {
        $log->pushHandler(new \Monolog\Handler\NullHandler($level));
    }
    return $log;
}

/**
 * 获取客户端IP地址
 * @param integer $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
 * @param boolean $adv 是否进行高级模式获取（有可能被伪装）
 * @return mixed
 */
function client_ip($type = 0, $adv = false)
{
    $type = $type ? 1 : 0;
    static $ip = NULL;
    if ($ip !== NULL)
        return $ip[$type];
    if ($adv) {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $pos = array_search('unknown', $arr);
            if (false !== $pos)
                unset($arr[$pos]);
            $ip = trim($arr[0]);
        }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
    } elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    // IP地址合法验证
    $long = sprintf("%u", ip2long($ip));
    $ip = $long ? array($ip, $long) : array('0.0.0.0', 0);
    return $ip[$type];
}

