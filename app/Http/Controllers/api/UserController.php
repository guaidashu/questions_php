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
    private $appid;
    private $sessionKey;

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

        $this->appid = config('app.app_id');
        $this->sessionKey = $data["session_key"];

        // $encrypt = new WXBizDataCrypt(config('app.app_id'), );

        $this->decryptData($data["encryptedData"], $data["iv"], $result);

        $result = json_decode($result);

        if (!empty($result->phoneNumber)) {
            $user->phone_number = $result->phoneNumber;
            $user->save();
            return successReply($result);
        } else {
            return errReply("手机号获取出错");
        }
    }

    /**
     * 检验数据的真实性，并且获取解密后的明文.
     * @param $encryptedData string 加密的用户数据
     * @param $iv string 与用户数据一同返回的初始向量
     * @param $data string 解密后的原文
     *
     * @return int 成功0，失败返回对应的错误码
     */
    public function decryptData($encryptedData, $iv, &$data)
    {
        if (strlen($this->sessionKey) != 24) {
            return ErrorCode::$IllegalAesKey;
        }
        $aesKey = base64_decode($this->sessionKey);


        if (strlen($iv) != 24) {
            return ErrorCode::$IllegalIv;
        }
        $aesIV = base64_decode($iv);

        $aesCipher = base64_decode($encryptedData);

        $result = openssl_decrypt($aesCipher, "AES-128-CBC", $aesKey, 1, $aesIV);

        $dataObj = json_decode($result);
        if ($dataObj == NULL) {
            return ErrorCode::$IllegalBuffer;
        }
        if ($dataObj->watermark->appid != $this->appid) {
            return ErrorCode::$IllegalBuffer;
        }
        $data = $result;
        return ErrorCode::$OK;
    }
}


class ErrorCode
{
    public static $OK = 0;
    public static $IllegalAesKey = -41001;
    public static $IllegalIv = -41002;
    public static $IllegalBuffer = -41003;
    public static $DecodeBase64Error = -41004;
}
