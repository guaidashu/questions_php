<?php
/**
 * Create by yy(github.com/guaidashu)
 * Date: 2020/9/23
 * Description:
 */


namespace App\Http\Tools\Ueditor;


class ConfigManager
{
    private $root_path;
    private $context_path;
    private $file_name;
    private $json_config;

    /**
     * ConfigManager constructor.
     * @param $root_path
     * @param $context_path
     * @param $file_name
     */
    public function __construct($root_path, $context_path, $file_name)
    {
        $this->root_path = $root_path;
        $this->context_path = $context_path;
        $this->file_name = $file_name;
        $this->initEnv();
    }

    /**
     * 工厂模式返回 ConfigManager实例
     *
     * @param $root_path
     * @param $context_path
     * @param $file_name
     * @return ConfigManager
     */
    public static function getConfigManager($root_path, $context_path, $file_name)
    {
        return new ConfigManager($root_path, $context_path, $file_name);
    }

    /**
     * 返回所有配置信息
     *
     * @return mixed
     */
    public function getAllConfig()
    {
        return $this->json_config;
    }

    /**
     * @param $path
     * @return string|string[]|null
     */
    public function readFile($path)
    {
        if (file_exists($path)) {
            $data = file_get_contents($path);
            return $this->filter($data);
        }

        return "{}";
    }

    /**
     *
     */
    public function initEnv()
    {
        $path = $this->root_path . $this->context_path . $this->file_name;
        $json_content = $this->readFile($path);
        $this->json_config = json_decode($json_content);
    }

    /**
     * @param $data
     * @return string|string[]|null
     */
    public function filter($data)
    {
        return preg_replace("/\/\*([\w\W]*?)\*\//", "", $data);
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return empty($this->json_config) ? false : true;
    }
}
