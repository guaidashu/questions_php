<?php
/**
 * Create by yy(github.com/guaidashu)
 * Date: 2020/8/26
 * Description:
 */


namespace App\Http\Controllers\admin;


use App\Http\Controllers\Controller;
use App\Model\HistoryModel;
use App\Model\UserModel;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    private $historyModel;

    /**
     * 构造函数
     *
     * HistoryController constructor.
     */
    public function __construct()
    {
        $this->historyModel = new HistoryModel();
    }

    /**
     * 获取答题历史记录数据(分页模式)
     *
     * @param Request $request
     * @return array|false|string
     */
    public function getHistoryList(Request $request)
    {
        $page = $request->query("page");
        if (empty($page)) {
            $page = 1;
        }

        $phone_number = $request->query("phone_number");
        $nickName = $request->query("nickName");
        // if (empty($phone_number)) {
        //     return errReply('请输入电话号码');
        // }

        // 先通过电话号码获取用户user_id
        $userModel = new UserModel();
        $uid = $userModel->getUidByPhoneNumber($phone_number, $nickName);

        if ($uid == 0 || empty($phone_number)) {
            $data = $this->historyModel->getHistoryListByUid($page);
        } else {
            $data = $this->historyModel->getHistoryListByUid($page, $uid);
        }

        return successReply($data);
    }

    /**
     * @param Request $request
     * @return array|false|string
     */
    public function getHistoryById(Request $request)
    {
        $history_id = $request->query("history_id");

        if (empty($history_id)) {
            return errReply("failed");
        }

        $data = $this->historyModel->getHistoryById($history_id);

        return successReply($data);
    }

    /**
     * @param Request $request
     *
     * 根据条件获取导出数据
     * @return array|false|string
     */
    public function getExportData(Request $request)
    {
        $phoneNumber = $request->query("phoneNumber");
        $nickName = $request->query("nickName");
        $startTime = $request->query("startTime");
        $endTime = $request->query("endTime");
        // 要导出的字段
        $columns = $request->query("columns");
        $userList = $request->query("userList");

        $data = $this->historyModel->getExportData($phoneNumber, $nickName, $startTime, $endTime, $columns, $userList);

        return successReply($data);
    }
}
