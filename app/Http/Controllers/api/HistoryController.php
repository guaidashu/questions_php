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

date_default_timezone_set('Asia/Shanghai');

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

        $N = 0;
        $phzScore = 0;
        $phzDesc = "";
        $phzConditioning = "";

        $data["physique_type"] = array();
        $data["physique_type_both"] = array();
        $data["physique_type_trend"] = array();

        // 进行体制 评判
        // 获取最大值
        $max = $data["result"][0]["score"];

        // 遍历分数值 取得最大分数
        foreach ($data["result"] as $k => $v) {

            if ($v["score"] >= 40) {
                $N++;
            }

            if ($v["physique_name"] == "平和体质") {
                $phzScore = $v["score"];
                $phzDesc = $v["physique_desc"];
                $phzConditioning = $v["conditioning"];
                continue;
            }

            if ($v["score"] > $max) {
                $max = $v["score"];
            }
        }

        // 取得第二高的分数
        $second = 0;
        foreach ($data["result"] as $k => $v) {

            if ($v["physique_name"] == "平和体质") {
                continue;
            }

            if ($v["score"] < $max && $v["score"] > $second) {
                $second = $v["score"];
            }
        }

        // 判断主体质类型
        if ($phzScore >= 60 && $max < 30) {
            $data["physique_type"][] = array(
                "name" => "平和质",
                "desc" => $phzDesc,
                "conditioning" => $phzConditioning
            );
        } else if ($phzScore >= 60 && $max >= 30 && $max <= 39) {
            // 基本是平和体质
            $data["physique_type"][] = array(
                "name" => "基本是平和质",
                "desc" => $phzDesc,
                "conditioning" => $phzConditioning
            );
            foreach ($data["result"] as $k => $v) {
                if ($max == $v["score"]) {
                    $data["physique_type_trend"][] = array(
                        "name" => $v["physique_name"],
                        "desc" => $v["physique_desc"],
                        "conditioning" => $v["conditioning"]
                    );
                }
            }
        } else if ($max >= 30 && $max <= 39) {
            // 平和质分数没有大于60的情况
            $data["physique_type"][] = array();
            foreach ($data["result"] as $k => $v) {
                if ($max == $v["score"]) {
                    $data["physique_type_trend"][] = array(
                        "name" => $v["physique_name"],
                        "desc" => $v["physique_desc"],
                        "conditioning" => $v["conditioning"]
                    );
                }
            }
        } else {
            // 应该判断多个主体质类型
            if ($max >= 40) {
                foreach ($data["result"] as $k => $v) {
                    if ($v["physique_name"] == "平和体质") {
                        continue;
                    }

                    if ($v["score"] == $max) {
                        $data["physique_type"][] = array(
                            "name" => $v["physique_name"],
                            "desc" => $v["physique_desc"],
                            "conditioning" => $v["conditioning"]
                        );
                    }
                }
            }

            // 判断X 是否大于等于 2 且 <= N
            if (count($data["physique_type"]) < $N && count($data["physique_type"]) >= 2) {
                // 只有主体质类型要显示
            } else {
                // 否则 进行其他逻辑
                if ($second >= 40) {
                    foreach ($data["result"] as $k => $v) {
                        if ($v["physique_name"] == "平和体质") {
                            continue;
                        }

                        if ($v["score"] == $second) {
                            $data["physique_type_both"][] = array(
                                "name" => $v["physique_name"],
                                "desc" => $v["physique_desc"],
                                "conditioning" => $v["conditioning"]
                            );
                        }
                    }
                }
            }
        }

        $data["physique_type"] = json_encode($data["physique_type"]);
        $data["physique_type_both"] = json_encode($data["physique_type_both"]);
        $data["physique_type_trend"] = json_encode($data["physique_type_trend"]);
        $data["answer"] = json_encode($data["answer"]);
        $data["result"] = json_encode($data["result"]);
        $data["height"] = $data["info"]["height"];
        $data["weight"] = $data["info"]["weight"];
        $data["age"] = $data["info"]["age"];
        $data["career"] = $data["info"]["career"];
        $data["created_at"] = date('Y/m/d h:i:s', time());
        unset($data["info"]);
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

    public function getHistoryListByUserId(Request $request)
    {
        $user_id = $request->query('user_id');

        $data = $this->historyModel->getHistoryByUserId($user_id);

        return successReply($data);
    }
}
