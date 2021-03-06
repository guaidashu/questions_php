<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Builder;
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
        $this->table = 'user';
    }

    /**
     * 初始化表
     */
    public function initTable()
    {
        // TODO: Implement initTable() method.
        // 判断表是否存在
        if (!Schema::hasTable($this->table)) {
            Schema::create($this->table, function (Blueprint $table) {
                $table->id()->comment('主键, 唯一id');
                $table->string('username')->comment('用户名');;
                $table->string('password')->nullable()->comment('用户密码');
                $table->string('remember_token')->nullable()->comment('记录token');
                $table->string('open_id')->comment('用户openid');
                $table->string('avatar_url')->nullable()->comment('头像url');
                $table->string('country')->nullable()->comment('国家');
                $table->string('province')->nullable()->comment('省');
                $table->string('city')->nullable()->comment('城市');
                $table->string('language')->nullable()->comment('语言');
                $table->smallInteger("sex")->default(3)->comment('性别'); // 1是 男性，2是女性
                $table->string('phone_number')->nullable()->comment('手机号码');
                $table->string('email')->nullable()->comment('邮箱');
                $table->smallInteger('status')->default(1)->comment('软删除标识');
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
        return UserModel::on()->where("status", "=", 1);
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
        return DB::table($this->table)->insertGetId($insertData);
    }

    /**
     * @param $open_id
     * @return Builder|Model|object|null
     *
     * 根据 openid 获取数据
     */
    public function getUserByOpenId($open_id)
    {
        return $this->queryData()->where("open_id", "=", $open_id)->first();
    }

    /**
     * 通过电话号码获取uid
     *
     * @param $phone_number
     * @param $nickName
     * @return int|int
     */
    public function getUidByPhoneNumber($phone_number, $nickName)
    {
        $data = $this->queryData()->where("phone_number", "=", $phone_number)->first();
        return !(empty($data->id)) ? $data->id : 0;
    }

    /**
     * @param $nickName
     * @return int|mixed
     */
    public function getUidByNickName($nickName)
    {
        $data = $this->queryData()->where("nickName", "=", $nickName)->first();
        return !(empty($data->id)) ? $data->id : 0;
    }

    /**
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection
     *
     * 获取所有用户
     */
    public function getAll() {
        return $this->queryData()->get(['id', 'username', 'phone_number']);
    }
}
