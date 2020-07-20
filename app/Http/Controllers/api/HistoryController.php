<?php
/**
 * Create by yy(github.com/guaidashu)
 * Date: 2020/7/12
 * Description:
 */


namespace App\Http\Controllers\api;


use App\Http\Controllers\Controller;
use App\Model\HistoryModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Class HistoryController
 * @package App\Http\Controllers\api
 *
 * 答题 历史管理
 */
class HistoryController extends Controller
{
    private $historyModel;

    /**
     * HistoryController constructor.
     *
     * 初始化模型
     */
    public function __construct()
    {
        $this->historyModel = new HistoryModel();
    }


    /**
     * @param Request $request
     *
     * 提交问题数据
     * @return array|false|string
     */
    public function submitResult(Request $request)
    {
        $data = $request->post();
        $data["answer"] = json_encode($data["answer"]);
        $data["result"] = json_encode($data["result"]);
        $data["created_at"] = date('Y/m/d h:i:s', time());
        $insert_id = $this->historyModel->insert($data);
        return successReply($insert_id);
    }

    /**
     * @param Request $request
     * @return array|false|string
     *
     * 通过id 获取对应的 答题历史记录
     */
    public function getHistoryResult(Request $request)
    {
        $id = $request->query('id');
        $data = $this->historyModel->getHistoryById($id);

        if (empty($data->id)) {
            return errReply("数据获取出错");
        }

        return successReply($data);
    }
}
