<?php
/**
 * Create by yy(github.com/guaidashu)
 * Date: 2020/6/4
 * Description:
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\PhysiqueModel;
use Illuminate\Http\Request;

/**
 * 体质 控制器
 *
 * Class PhysiqueController
 * @package App\Http\Controllers\Admin
 */
class PhysiqueController extends Controller
{
    /**
     * 构造函数
     *
     * PhysiqueController constructor.
     */
    public function __construct()
    {
    }

    public function getPhysiqueList(Request $request)
    {
        // $physiqueModel = new PhysiqueModel();
        $data = PhysiqueModel::all();
        return successReply("ok");
    }

    public function addPhysique(Request $request)
    {
        $data = $request->post();
        $physiqueModel = new PhysiqueModel();
        $result = $physiqueModel->insert($data);
        return successReply($result);
    }
}
