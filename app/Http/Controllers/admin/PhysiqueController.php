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
        $data = $this->physiqueModel->getAllData();
        return successReply(pagination($data, count($data)));
    }

    public function getPhysiqueConditioning() {
        $data = $this->physiqueModel->getConditioning();

        return successReply($data);
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
        $data["desc"] = base64_decode($data["desc"]);
        $data["conditioning"] = base64_decode($data["conditioning"]);
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
        $id = $request->query("id");
        $result = $this->physiqueModel->deletePhysique($id);
        return successReply($result);
    }

    /**
     * @param Request $request
     * @return array|false|string
     *
     * 更新成功
     */
    public function updatePhysique(Request $request)
    {
        $data = $request->post();

        $model = $this->physiqueModel->queryData()->find($data["id"]);

        $model->level = $data["level"];
        $model->name = $data["name"];
        $model->desc = base64_decode($data["desc"]);
        $model->conditioning = base64_decode($data["conditioning"]);

        return successReply($model->save());
    }

    /**
     * @param Request $request
     * @return array|false|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|string|null
     */
    public function getConditioningById(Request $request)
    {
        $conditioning_id = $request->query("conditioning_id");

        if (empty($conditioning_id)) {
            return errReply();
        }

        $data = $this->physiqueModel->getConditioningById($conditioning_id);

        return successReply($data);
    }
}
