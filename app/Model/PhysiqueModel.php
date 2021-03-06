<?php
/**
 * Create by yy(github.com/guaidashu)
 * Date: 2020/6/4
 * Description:
 */

namespace App\Model;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

date_default_timezone_set('Asia/Shanghai');

class PhysiqueModel extends Model implements BaseModel
{
    /**
     * 表名
     *
     * @var string
     */
    protected $table;

    /**
     * 默认值
     *
     * @var array
     */
    protected $attributes = [
    ];

    /**
     * 构造函数
     *
     * PhysiqueModel constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = "physique";
    }

    /**
     * 初始化表
     *
     */
    public function initTable()
    {
        // TODO: Implement initTable() method.
        if (!Schema::hasTable($this->table)) {
            Schema::create($this->table, function (Blueprint $table) {
                $table->id()->comment('唯一主键id');
                $table->integer('level')->comment('题目显示优先级');
                $table->string('name')->comment('体质名字');
                $table->string('desc')->comment('体质描述');
                $table->integer('conditioning')->comment('调理方法');
                $table->smallInteger('status')->default(1)->comment('软删除标识');
                $table->timestamp('created_at', 0)->nullable()->comment('创建时间');
                $table->timestamp('updated_at', 0)->nullable()->comment('更新时间');
            });
        }
    }

    /**
     * 获取表名
     *
     * @return mixed
     */
    public function getTableName()
    {
        // TODO: Implement getTableName() method.
        return $this->table;
    }

    /**
     * @return Builder
     */
    public function queryData()
    {
        // TODO: Implement getDB() method.
        return PhysiqueModel::on()->where("status", "=", 1);
    }

    /**
     * 插入数据
     *
     * @param $insertData
     * @return mixed
     */
    public function insert($insertData)
    {
        // TODO: Implement insert() method.
        $insertData["created_at"] = date('Y-m-d h:i:s', time());
        return DB::table($this->table)->insertGetId($insertData);
    }

    /**
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection
     *
     * 获取所有数据
     */
    public function getAllData()
    {
        return $this->queryData()->get();
    }

    /**
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getConditioning()
    {
        return $this->queryData()->get(['id', 'conditioning']);
    }

    /**
     * @param $id
     * @return bool
     *
     * 软删除 体质类型
     */
    public function deletePhysique($id)
    {
        $data = $this->queryData()->where("id", "=", $id)->first();
        $data->status = 0;
        return $data->save();
    }

    /**
     * @param $id
     * @return Builder|Model|object|null
     */
    public function getConditioningById($id)
    {
        return $this->queryData()->where("id", "=", $id)->first(['conditioning']);
    }
}
