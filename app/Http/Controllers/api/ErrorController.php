<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

/**
 * 错误控制
 *
 * Class ErrorController
 * @package App\Http\Controllers\Api
 */
class ErrorController extends Controller
{
    public function __construct()
    {
    }

    /**
     * 出错 会 显示的数据
     * @return array|false|string
     */
    public function error()
    {
        return errReply("", 1, "error");
    }
}
