<?php
/**
 * Create by yy(github.com/guaidashu)
 * Date: 2020/7/12
 * Description:
 */


namespace App\Http\Controllers\api;


use App\Http\Controllers\Controller;
use App\Model\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    private $userModel;

    /**
     * UserController constructor.
     *
     * 构造函数 初始化model
     */
    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * @param Request $request
     * @return array|false|string
     *
     * 用户登录 ，从微信端通过 code 换取 openId, sessionKey, unionId
     */
    public function login(Request $request)
    {
        $code = $request->query('code');
        $url = "https://api.weixin.qq.com/sns/jscode2session?appid=".config('app.app_id')."&secret=".config('app.app_secret')."&js_code=".$code."&grant_type=authorization_code";
        $data = getInfo($url);

        $result = json_encode($data);

        if (empty($result["openid"])) {
            return errReply("empty open_id");
        }

        $user = $this->userModel->getUserByOpenId($result["openid"]);

        if (empty($user->id)) {
            // 创建新用户
            $this->userModel->insert(array(
               "open_id" => $result["openid"]
            ));
        }

        return successReply($data);
    }
}
