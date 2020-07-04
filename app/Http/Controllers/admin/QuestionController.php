<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Model\QuestionModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Class QuestionController
 * @package App\Http\Controllers\admin
 */
class QuestionController extends Controller
{
    private $questionModel;

    /**
     * QuestionController constructor.
     */
    public function __construct()
    {
        $this->questionModel = new QuestionModel();
    }

    /**
     * @param Request $request
     * @return array|false|string
     * 获取问题列表
     */
    public function getQuestionList(Request $request)
    {
        $page = $request->query("page", 1);
        $size = $request->query("size", 10);
        $questionList = $this->questionModel->getList($page, $size);
        return successReply($questionList);
    }

    /**
     * @param Request $request
     * @return array|false|string
     * 获取 所有 问题
     */
    public function getAllQuestion(Request $request)
    {
        $questionList = $this->questionModel->getAll();
        return successReply($questionList);
    }

    /**
     * @param Request $request
     * @return array|false|string
     * 添加问题
     */
    public function addQuestion(Request $request)
    {
        $data = $request->post();
        // $data["content"] = base64_decode($data["content"]);
        $data["answer"] = json_encode($data["answer"]);
        $data["created_at"] = date('Y-m-d h:i:s', time());
        $result = $this->questionModel->insert($data);
        return successReply($result);
    }
}
