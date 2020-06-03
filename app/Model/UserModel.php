<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UserModel extends Model implements BaseModel
{

    /**
     * 表名
     *
     * @var string
     */
    private $tableName;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->tableName = 'user';
    }

    /**
     * 初始化表
     */
    public function initTable()
    {
        // TODO: Implement initTable() method.
        // 判断表是否存在
        if (!Schema::hasTable($this->tableName)) {
            Schema::create('user', function (Blueprint $table) {
                $table->id()->comment('主键, 唯一id');
                $table->string('username')->comment('用户名');;
                $table->string('password')->comment('用户密码');
                $table->string('remember_token')->comment('记录token');
                $table->string('email')->comment('邮箱');
                $table->timestamp('created_at', 0)->nullable()->comment('创建时间');
                $table->timestamp('updated_at', 0)->nullable()->comment('更新时间');
            });
        }
    }

    /**
     * 获取表名
     *
     * @return string
     */
    public function tableName()
    {
        // TODO: Implement tableName() method.
        return $this->tableName;
    }

    /**
     * 插入数据
     *
     * @param $insertData
     * @return int
     */
    public function insert($insertData)
    {
        // TODO: Implement insert() method.
        return DB::table($this->tableName)->insertGetId($insertData);
    }
}
