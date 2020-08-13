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

date_default_timezone_set('Asia/Shanghai');

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
                $table->longText('answer')->comment('用户的答案');
                $table->longText('result')->comment('用户得分');
                $table->text('physique_type')->nullable()->comment('体质类型');
                $table->text('physique_type_both')->nullable()->comment('兼有类型');
                $table->text('physique_type_trend')->nullable()->comment('倾向类型');
                $table->smallInteger('status')->default(1)->comment('软删除标识');
                $table->integer('height')->nullable()->comment('身高');
                $table->integer('age')->nullable()->comment('年龄');
                $table->integer('weight')->nullable()->comment('体重');
                $table->text('career')->nullable()->comment('职业');
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
     * @param $value
     * @return false|string
     */
    public function setAnswerAttribute($value)
    {
        return json_encode($value);
    }

    /**
     * @param $value
     * @return mixed
     */
    public function getAnswerAttribute($value)
    {
        return json_decode($value);
    }

    /**
     * @param $value
     * @return false|string
     */
    public function setResultAttribute($value)
    {
        return json_encode($value);
    }

    /**
     * @param $value
     * @return mixed
     */
    public function getResultAttribute($value)
    {
        return json_decode($value);
    }

    /**
     * @param $value
     * @return false|string
     *
     * 创建日期 数据赋值
     */
    public function setCreatedAtAttribute($value)
    {
        return date('Y/m/d h:i:s', time());
    }

    public function getCreatedAtAttribute($value)
    {
        return date('Y/m/d h:i', strtotime($value));
    }

    /**
     * @param $value
     * @return mixed
     *
     * 体质类型
     */
    public function getPhysiqueTypeAttribute($value) {
        return json_decode($value);
    }

    /**
     * @param $value
     * @return mixed
     *
     * 兼有体质类型
     */
    public function getPhysiqueTypeBothAttribute($value) {
        return json_decode($value);
    }

    /**
     * @param $value
     * @return mixed
     *
     * 倾向体质类型
     */
    public function getPhysiqueTypeTrendAttribute($value) {
        return json_decode($value);
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

    /**
     * @param $id
     * @return Builder|Model|object|null
     *
     * 通过id 获取 对应的记录
     */
    public function getHistoryById($id)
    {
        return $this->queryData()->where("id", "=", $id)->first();
    }

    /**
     * @param $user_id
     * @return \Illuminate\Database\Eloquent\Collection
     *
     * 通过user_id 获取答题历史记录
     */
    public function getHistoryByUserId($user_id)
    {
        return $this->queryData()->where("user_id", "=", $user_id)->get();
    }

}
