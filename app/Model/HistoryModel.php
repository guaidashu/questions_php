<?php
/**
 * Create by yy(github.com/guaidashu)
 * Date: 2020/7/3
 * Description: History Model
 */


namespace App\Model;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class HistoryModel extends Model implements BaseModel
{
    /**
     * 表名
     *
     * @var
     */
    protected $table;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    /**
     * 默认值
     *
     * @var array
     */
    protected $attributes = [
    ];

    /**
     * QuestionModel constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = 'history';
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
                $table->id()->comment('主键id');
                $table->bigInteger('user_id')->comment('用户id');
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
        return HistoryModel::on()->where("status", "=", 1);
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
        return DB::table($this->table)->insertGetId($insertData);
    }
}
