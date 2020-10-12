<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Model\UserModel;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * @param Request $request
     * @return array|false|string
     *
     * 登录
     */
    public function login(Request $request)
    {
        $data = $request->post();
        $userName = $data["userName"];
        $password = $data["password"];

        if ($userName == "super_admin") {
            if ($password == "jyr123") {
                return successReply(array(
                    "token" => "super_admin"
                ));
            } else {
                return errReply();
            }
        }

        if ($userName == "admin" && $password == "jyr345") {
            return successReply(array(
                "token" => "user"
            ));
        }

        return errReply();

    }

    /**
     * @param Request $request
     * @return array|false|string
     *
     * 获取信息
     */
    public function getInfo(Request $request)
    {

        $token = $request->post();

        $token = $token["token"];

        if ($token == "super_admin") {
            $data = array(
                "user_id" => "1",
                "name" => "admin",
                "avatar" => "",
                "access" => array(
                    "super_admin"
                )
            );
        } else {
            $data = array(
                "user_id" => "1",
                "name" => "admin",
                "avatar" => "",
                "access" => array(
                    "admin"
                )
            );
        }

        return successReply($data);
    }

    /**
     * @param Request $request
     * @return array|false|string
     *
     * 登出
     */
    public function logout(Request $request)
    {
        return successReply("ok");
    }

    /**
     * @param Request $request
     * @return array|false|string
     *
     * 获取用户列表
     */
    public function getUserList(Request $request)
    {
        $list = $this->userModel->getAll();
        return successReply($list);
    }
}
