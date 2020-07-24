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
    private $physiqueModel;

    /**
     * 构造函数
     *
     * PhysiqueController constructor.
     */
    public function __construct()
    {
        $this->physiqueModel = new PhysiqueModel();
    }

    /**
     * 获取所有 体质 数据
     *
     * @param Request $request
     * @return array|false|string
     */
    public function getPhysiqueList(Request $request)
    {
        // $physiqueModel = new PhysiqueModel();
        $data = PhysiqueModel::all();
        return successReply(pagination($data, count($data)));
    }

    /**
     * 新增体质数据
     *
     * @param Request $request
     * @return array|false|string
     */
    public function addPhysique(Request $request)
    {
        $data = $request->post();
        $result = $this->physiqueModel->insert($data);
        return successReply($result);
    }

    /**
     * @param Request $request
     * @return array|false|string
     *
     * 删除 体质类型
     */
    public function deletePhysique(Request $request)
    {
        $delete_id = $request->query('delete_id');
        $result = $this->physiqueModel->deletePhysique($delete_id);
        return successReply($result);
    }
}
