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
