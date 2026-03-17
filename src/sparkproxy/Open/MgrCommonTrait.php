<?php

namespace SparkProxy\Open;

trait MgrCommonTrait
{
    /**
     * 查询统计数据。
     *
     * @param string $sql SQL 查询语句。
     *
     * @return array [result, responseInfo]
     */
    public function queryData($sql)
    {
        return $this->post('MgrQueryData', [
            "sql" => $sql
        ]);
    }
}
