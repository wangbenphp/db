<?php

namespace wangben\db\service;

/**
 * 数据处理类
 */
class DataHandle
{
    /**
     * 查询数据处理
     */
    public function select($object, $type = 2, $field = '')
    {
        while($row = $object->fetch(\PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }

        if ($type == 1) {
            $result = $data[0];
        } else {
            $result = $data;
        }

        return $result;
    }
}