<?php

namespace App\Model;

use \Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

const SEX = [
    '男',
    '女',
    '不限'
];

class QuestionModel extends Model implements BaseModel
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
        $this->table = 'question';
    }

    /**
     * 初始化表
     */
    public function initTable()
    {
        // TODO: Implement initTable() method.
        if (!Schema::hasTable($this->table)) {
            Schema::create($this->table, function (Blueprint $table) {
                $table->id()->comment('主键id');
                $table->text('content')->comment('问题内容');
                $table->unsignedTinyInteger('sex')->comment('性别'); // 这里的性别是检索条件
                $table->text('answer')->comment('分数序列化json数据'); // 用户要选择的答案
                $table->unsignedSmallInteger('body_type')->comment('体质类型');
                $table->smallInteger('level')->comment('显示优先级');
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
     * @param $value
     * @return mixed
     *
     * 获取 性别的数据处理
     */
    public function getSexAttribute($value)
    {
        return SEX[$value - 1];
    }

    /**
     * @param $value
     *
     * 获取 答案数据，其实就是decode一下
     * @return mixed
     */
    public function getAnswerAttribute($value)
    {
        return json_decode($value);
    }

    /**
     * @return HasOne
     *
     * 体质关联的数据
     */
    public function physique()
    {
        return $this->hasOne(PhysiqueModel::class, 'id', 'body_type');
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
     * @param int $page
     * @param int $size
     * @return array
     *
     * 获取问题列表
     */
    public function getList($page = 1, $size = 10)
    {
        // $db = DB::table($this->table);
        $db = QuestionModel::with('physique')->where("status", '=', 1);
        $count = $db->count("id");
        $data = $db->skip(getOffset($page, $size))->take($size)->get();
        return pagination($data, $count);
    }

    /**
     * @return mixed
     *
     * 获取所有
     */
    public function getAll() {
        return QuestionModel::with('physique')->get();
    }
}
