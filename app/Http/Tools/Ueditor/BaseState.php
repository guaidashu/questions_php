<?php
/**
 * Create by yy(github.com/guaidashu)
 * Date: 2020/9/23
 * Description:
 */


namespace App\Http\Tools\Ueditor;


class BaseState extends State
{
    public $info;

    /**
     * BaseState constructor.
     * @param $state
     * @param $info_code
     */
    public function __construct($state, $info_code)
    {
        parent::__construct();
        $this->state = $state;
        $this->info = AppInfo::getStateInfo($info_code);
    }

    /**
     * @param $info
     */
    public function setInfo($info)
    {
        $this->info = $info;
    }
}
