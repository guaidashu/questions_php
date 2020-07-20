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
        $user_info = $request->query('user_info');

        $url = "https://api.weixin.qq.com/sns/jscode2session?appid=" . config('app.app_id') . "&secret=" . config('app.app_secret') . "&js_code=" . $code . "&grant_type=authorization_code";
        $data = getInfo($url);

        $result = json_decode($data);
        $userInfo = json_decode($user_info);

        if (empty($result->openid)) {
            return errReply("empty open_id, msg: " . json_encode($result));
        }

        $user = $this->userModel->getUserByOpenId($result->openid);

        $result->user_id = 0;

        if (empty($user->id)) {
            // 创建新用户
            $result->user_id = $this->userModel->insert(array(
                "open_id" => $result->openid,
                "username" => $userInfo->nickName,
                "city" => $userInfo->city,
                "country" => $userInfo->country,
                "sex" => $userInfo->gender,
                "language" => $userInfo->language,
                "province" => $userInfo->province,
                "avatar_url" => $userInfo->avatarUrl
            ));
        } else {
            $result->user_id = $user->id;
        }

        return successReply($result);
    }
}
