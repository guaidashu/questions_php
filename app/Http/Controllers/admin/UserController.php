<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;

class UserController
{
    public function __construct()
    {
    }

    public function login()
    {
        return successReply(array(
            "token" => "jyr"
        ));
    }

    public function getInfo()
    {
        return successReply("guaidashu");
    }
}
