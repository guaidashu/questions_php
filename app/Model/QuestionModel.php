<?php

namespace App\Model;

use \Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class QuestionModel extends Model implements BaseModel
{
    /**
     * 表名
     *
     * @var
     */
    private $tableName;

    /**
     * QuestionModel constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->tableName = 'question';
    }

    /**
     * 初始化表
     */
    public function initTable()
    {
        // TODO: Implement initTable() method.
        if (!Schema::hasTable($this->tableName)) {
            Schema::create($this->tableName, function (Blueprint $table) {
                $table->id()->comment('主键id');
                $table->text('content')->comment('问题内容');
                $table->unsignedTinyInteger('sex')->comment('性别');
                $table->text('rate')->comment('分数序列化json数据');
                $table->unsignedSmallInteger('body_type')->comment('体质类型');
                // $table->string('')->comment('');
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
        return $this->tableName;
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
        return DB::table($this->tableName)->insertGetId($insertData);
    }
}
