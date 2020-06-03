<?php

namespace App\Model;

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
    public function tableName();

    /**
     * 插入数据
     *
     * @param $insertData
     * @return mixed
     */
    public function insert($insertData);
}
