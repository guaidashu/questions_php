<?php
/**
 * Create by yy(github.com/guaidashu)
 * Date: 2020/7/12
 * Description:
 */

namespace App\Http\Controllers\api;


use App\Http\Controllers\Controller;
use App\Http\Controllers\decrypt\WXBizDataCrypt;
use App\Model\UserModel;
use Illuminate\Http\Request;

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
                "avatar_url" => $userInfo->avatarUrl,
                "created_at" => date('Y-m-d h:i:s', time())
            ));
        } else {
            $result->user_id = $user->id;
        }

        return successReply($result);
    }

    /**
     * @param Request $request
     * @return array|false|string
     *
     * 解密 手机号码 并 存到对应用户信息
     */
    public function decryptPhoneNumber(Request $request)
    {
        $data = $request->post();

        $user = $this->userModel->queryData()->find($data["user_id"]);

        // 判断用户是否 已经有电话号码，已经存在则直接返回即可
        if (!empty($user->phone_number)) {
            return successReply(array(
                "purePhoneNumber" => $user->phone_number
            ));
        }

        $result = '';

        $encrypt = new WXBizDataCrypt(config('app.app_id'), $data["session_key"]);

        $encrypt->decryptData($data["encryptedData"], $data["iv"], $result);

        $result = json_decode($result);

        if (!empty($result->phoneNumber)) {
            $user->phone_number = $result->phoneNumber;
            $user->save();
            return successReply($result);
        } else {
            return errReply("手机号获取出错");
        }
    }
}
