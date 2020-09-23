<?php
/**
 * Create by yy(github.com/guaidashu)
 * Date: 2020/9/23
 * Description:
 */


namespace App\Http\Tools\Ueditor;


use Illuminate\Http\Request;

class UeditorAction
{
    private $request;
    private $action_type;
    private $config_manager;

    /**
     * UeditorAction constructor.
     * @param $request Request
     * @param $root_path
     * @param $context_path
     * @param $file_name
     */
    public function __construct($request, $root_path, $context_path, $file_name)
    {
        $this->request = $request;
        $this->action_type = $request->query("action");
        $this->config_manager = ConfigManager::getConfigManager($root_path, $context_path, $file_name);
    }

    /**
     * 启动函数
     */
    public function exec()
    {
        return $this->invoke();
    }

    /**
     * @return BaseState|mixed
     */
    public function invoke()
    {
        if (array_key_exists($this->action_type, ActionMap::getActionMap())) {
            if (!empty($this->config_manager) && $this->config_manager->valid()) {
                $action_code = ActionMap::getActionType($this->action_type);
                return $this->switch($action_code);
            } else {
                return new BaseState(false, 102);
            }
        }

        return new BaseState(false, 101);
    }

    /**
     * @param $action_code
     * @return mixed
     */
    public function switch($action_code)
    {
        $action = array(
            0 => $this->getAllConfig($action_code),
            1 => $this->uploadFile($action_code),
            2 => $this->uploadFile($action_code),
            3 => $this->uploadFile($action_code),
            4 => $this->uploadFile($action_code),
            5 => $this->catchimage($action_code),
            6 => $this->uploadList($action_code),
            7 => $this->uploadList($action_code)
        );

        return $action[$action_code];
    }

    public function uploadList($action_code)
    {
        return "";
    }

    private function getAllConfig($action_code)
    {
        return $this->config_manager->getAllConfig();
    }

    private function uploadFile($action_code)
    {
        return "";
    }

    private function catchimage($action_code)
    {
        return "";
    }
}
