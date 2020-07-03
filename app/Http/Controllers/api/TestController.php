<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

/**
 * 测试控制器
 *
 * Class TestController
 * @package App\Http\Controllers\Api
 */
class TestController extends Controller
{
    public function __construct()
    {
    }

    /**
     * 检测token 中间件
     *
     * @return array|false|string
     */
    public function testToken()
    {
        return successReply("ok");
    }

    /**
     * 检测小程序是否连通
     *
     * @return array|false|string
     */
    public function testMiniProgram()
    {
        return successReply(config('app.app_id'));
    }
}
