<?php
/**
 * Create by yy(github.com/guaidashu)
 * Date: 2020/9/23
 * Description:
 */


namespace App\Http\Controllers\admin;


use App\Http\Controllers\Controller;
use App\Http\Tools\Ueditor\UeditorAction;
use Illuminate\Http\Request;

class UeditorController extends Controller
{
    public function __construct()
    {
    }

    /**
     * @param Request $request
     * @return array|false|string
     */
    public function action(Request $request)
    {
        $ueditor = new UeditorAction($request, "", "", "config.json");
        return json_encode($ueditor->exec());
    }

    /**
     * @param Request $request
     * @return array|false|string
     */
    public function file(Request $request)
    {
        $myfile = fopen("testfile.txt", "w");
        return successReply("ok");
    }
}
