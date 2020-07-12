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
        $this->historyModel->insert($data);
        return successReply("ok");
    }
}
