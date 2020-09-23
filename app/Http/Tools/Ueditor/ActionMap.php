<?php
/**
 * Create by yy(github.com/guaidashu)
 * Date: 2020/9/23
 * Description:
 */


namespace App\Http\Tools\Ueditor;


class ActionMap
{
    private static $action_map = array(
        "config" => 0,
        "uploadimage" => 1,
        "uploadscrawl" => 2,
        "uploadvideo" => 3,
        "uploadfile" => 4,
        "catchimage" => 5,
        "listfile" => 6,
        "listimage" => 7
    );

    public function __construct()
    {
    }

    /**
     * 获取整个类型列表
     */
    public static function getActionMap()
    {
        return self::$action_map;
    }

    /**
     * 通过键值 获取单个 类型
     * @param $key
     * @return mixed
     */
    public static function getActionType($key)
    {
        return self::$action_map[$key];
    }
}
