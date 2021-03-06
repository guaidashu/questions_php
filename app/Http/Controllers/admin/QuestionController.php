<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Model\QuestionModel;
use Illuminate\Http\Request;

date_default_timezone_set('Asia/Shanghai');

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
     *
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
     *
     * 添加问题
     */
    public function addQuestion(Request $request)
    {
        $data = $request->post();
        $data["created_at"] = date('Y-m-d h:i:s', time());
        $data["answer"] = json_encode($data["answer"]);
        $result = $this->questionModel->insert($data);
        return successReply($result);
    }

    /**
     * @param Request $request
     * @return array|false|string
     *
     * 更新问题数据
     */
    public function updateQuestion(Request $request)
    {
        $data = $request->post();
        $model = $this->questionModel->queryData()->find($data['id']);
        $model->sex = $data['sex'];
        $model->content = $data['content'];
        $model->level = $data['level'];
        $model->answer = $data['answer'];
        $model->body_type = $data['body_type'];
        $model->repeat = $data["repeat"];
        $model->repeat_id = $data["repeat_id"];
        $model->is_show = $data["is_show"];
        $result = $model->save();
        return successReply($result);
    }

    /**
     * @param Request $request
     * @return array|false|string
     * @throws \Exception
     *
     * 删除数据
     */
    public function deleteQuestion(Request $request)
    {
        $id = $request->query("id");
        $result = $this->questionModel->deleteQuestion($id);
        return successReply($result);
    }
}
