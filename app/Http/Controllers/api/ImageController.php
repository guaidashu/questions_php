<?php
/**
 * Create by yy(github.com/guaidashu)
 * Date: 2021/1/9
 * Description:
 */


namespace App\Http\Controllers\api;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function __construct()
    {

    }

    public function img(Request $request)
    {
        $imgName = $request->query("id");

        $img = "https://jyrwechat.oss-cn-chengdu.aliyuncs.com/images/" . $imgName;

        return view('index', ['img' => $img]);
    }
}
