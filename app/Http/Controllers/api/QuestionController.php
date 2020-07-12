<?php
/**
 * Create by yy(github.com/guaidashu)
 * Date: 2020/7/12
 * Description:
 */


namespace App\Http\Controllers\api;


use App\Http\Controllers\Controller;
use App\Model\QuestionModel;
use Illuminate\Http\Request;

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
     *
     * 获取 所有 问题
     */
    public function getAllQuestion(Request $request)
    {
        $questionList = $this->questionModel->getAll();
        return successReply($questionList);
    }
}
