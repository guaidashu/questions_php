<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Builder;

interface BaseModel
{
    /**
     * 初始化表
     *
     * @return mixed
     */
    public function initTable();

    /**
     * 获取表名
     *
     * @return mixed
     */
    public function getTableName();

    /**
     * 插入数据
     *
     * @param $insertData
     * @return mixed
     */
    public function insert($insertData);

    /**
     * @return Builder
     */
    public function queryData();
}
