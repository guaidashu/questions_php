<?php

namespace App\Http\Controllers\Api;

use App\Model\QuestionModel;
use App\Model\UserModel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class CreateTableController extends Controller
{

    public function __construct()
    {
    }

    /**
     * 建表
     * 所有数据库初始化调用此函数即可
     */
    public function createTable()
    {
        Log::info("createTable");
        $arr = array(
            new UserModel(),
            new QuestionModel()
        );
        $this->create($arr);

        return successReply("ok");
    }

    /**
     * 遍历建表
     *
     * @param $arr
     */
    public function create($arr)
    {
        foreach ($arr as $k => $v) {
            $v->initTable();
        }
    }

}
