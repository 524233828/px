<?php
namespace JoseChan\Base\Api\Logic;

/**
 * 逻辑基类
 * Class Logic
 * @package JoseChan\Base\Api\Logic
 */
abstract class Logic
{

    private static $instance;

    /**
     * 获取单例
     * @param $fd
     * @return $this
     */
    public static function getInstance($fd = null)
    {
        $class_name = !empty($fd) ? $fd : get_called_class();

        if (!isset(self::$instance[$class_name]) || !self::$instance[$class_name] instanceof self) {
            self::$instance[$class_name] = new static;
        }

        return self::$instance[$class_name];
    }

    /**
     * 删除对象
     * @param $class_name
     */
    public static function removeInstance($class_name)
    {
        unset(self::$instance[$class_name]);
    }

    public function returnJson($data)
    {
        $data = $data ?: [];
        return json_encode($data, JSON_UNESCAPED_UNICODE);
    }

}
