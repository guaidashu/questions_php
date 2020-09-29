<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController
{
    public function __construct()
    {
    }

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
        }else {
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

    public function logout(Request $request)
    {
        return successReply("ok");
    }
}
