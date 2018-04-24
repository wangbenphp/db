<?php

namespace wangben\db\service;

class Builder
{
    // SQL表达式
    protected $selectSql    = 'SELECT%DISTINCT% %FIELD% FROM %TABLE%%FORCE%%JOIN%%WHERE%%GROUP%%HAVING%%UNION%%ORDER%%LIMIT%%LOCK%%COMMENT%';
    protected $insertSql    = '%INSERT% INTO %TABLE% (%FIELD%) VALUES (%DATA%) %COMMENT%';
    protected $insertAllSql = '%INSERT% INTO %TABLE% (%FIELD%) %DATA% %COMMENT%';
    protected $updateSql    = 'UPDATE %TABLE% SET %SET% %JOIN% %WHERE% %ORDER%%LIMIT% %LOCK%%COMMENT%';
    protected $deleteSql    = 'DELETE FROM %TABLE% %USING% %JOIN% %WHERE% %ORDER%%LIMIT% %LOCK%%COMMENT%';

    /**
     * 生成查询SQL
     */
    public function select($options = [])
    {
        $options = $this->optionsReset($options);

        $sql = str_replace(
            ['%TABLE%', '%DISTINCT%', '%FIELD%', '%JOIN%', '%WHERE%', '%GROUP%', '%HAVING%', '%ORDER%', '%LIMIT%', '%UNION%', '%LOCK%', '%COMMENT%', '%FORCE%'],
            [
                $this->parseTable($options['table'], $options),
                $this->parseDistinct($options['distinct']),
                $this->parseField($options['field']),
                $this->parseJoin($options['join'], $options),
                $this->parseWhere($options['where']),
                $this->parseGroup($options['group']),
                $this->parseHaving($options['having']),
                $this->parseOrder($options['order']),
                $this->parseLimit($options['limit']),
                $this->parseUnion($options['union']),
                $this->parseLock($options['lock']),
                $this->parseComment($options['comment']),
                $this->parseForce($options['force']),
            ], $this->selectSql);

        return $sql;
    }

    private function optionsReset($options = [])
    {
        $options['table']    = isset($options['table']) ? $options['table'] : '';
        $options['alias']    = isset($options['alias']) ? $options['alias'] : '';
        $options['distinct'] = isset($options['distinct']) ? $options['distinct'] : '';
        $options['field']    = isset($options['field']) ? $options['field'] : '';
        $options['join']     = isset($options['join']) ? $options['join'] : '';
        $options['where']    = isset($options['where']) ? $options['where'] : '';
        $options['group']    = isset($options['group']) ? $options['group'] : '';
        $options['having']   = isset($options['having']) ? $options['having'] : '';
        $options['order']    = isset($options['order']) ? $options['order'] : '';
        $options['limit']    = isset($options['limit']) ? $options['limit'] : '';
        $options['union']    = isset($options['union']) ? $options['union'] : '';
        $options['lock']     = isset($options['lock']) ? $options['lock'] : '';
        $options['comment']  = isset($options['comment']) ? $options['comment'] : '';
        $options['force']    = isset($options['force']) ? $options['force'] : '';

        return $options;
    }

    /**
     * table分析
     */
    private function parseTable($table, $options)
    {
        if ($options['alias']) {
            $tables = '`' . $table . '` `' . $options['alias'] . '` ';
        } else {
            $tables = '`' . $table . '` ';
        }
        return $tables;
    }

    private function parseDistinct($data)
    {
        return '';
    }

    /**
     * field分析
     */
    private function parseField($field = '')
    {
        if ($field) {
            $arr1   = explode(',', $field);
            $sign   = '';
            $fields = '';
            foreach ($arr1 as $vv) {
                $v = trim($vv);
                if (strpos($vv, '.') !== false) {
                    $arr2    = explode('.', $vv);
                    $fields .= $sign . '`' . $arr2[0] . '`.`' . $arr2[1] . '` ';
                } else {
                    $fields .= $sign . '`' . $v . '` ';
                }
                $sign    = ',';
            }
        } else {
            $fields = '*';
        }

        return $fields;
    }

    private function parseJoin($join, $options)
    {
        return '';
    }

    /**
     * where分析
     */
    private function parseWhere($where = [])
    {
        if (!empty($where)) {

            $sql = 'WHERE ';

            foreach ($where as $kk => $vv) {
                foreach ($vv as $kkk => $vvv) {
                    foreach ($vvv as $vvvv) {

                        $sign = $kk . ' ';

                        if (strpos($kkk, '.') !== false) {
                            $arr2    = explode('.', $kkk);
                            $sql    .= $sign . '`' . $arr2[0] . '`.`' . $arr2[1] . '` ' . $vvvv[0] . ' ' . $vvvv[1] . ' ';
                        } else {
                            $sql    .= $sign . '`' . $kkk . '` ' . $vvvv[0] . ' ' . $vvvv[1] . ' ';
                        }
                    }
                }
            }

            $sql = str_replace('WHERE AND', 'WHERE', $sql);

        } else {
            $sql = '';
        }

        return $sql;
    }

    private function parseGroup($data)
    {
        return '';
    }

    private function parseHaving($data)
    {
        return '';
    }

    private function parseOrder($order = [])
    {
        $orders = 'ORDER BY ';
        $sign   = ' ';

        if (empty($order)) {
            $orders = '';
        } else {
            foreach ($order as $k => $v) {
                $k = trim($k);
                if (strpos($k, '.') !== false) {
                    $arr2    = explode('.', $k);
                    $orders .= $sign . '`' . $arr2[0] . '`.`' . $arr2[1] . '` ' . $v . ' ';
                } else {
                    $orders .= $sign . '`' . $k . '` ' . $v . ' ';
                }
                $sign    = ', ';
            }
        }

        return $orders;
    }

    private function parseLimit($limit = [])
    {
        if (empty($limit)) {
            $limits = '';
        } else {
            if ($limit[0] == 0) {
                $limits = 'LIMIT ' . $limit[1] . ' ';
            } else {
                $limits = 'LIMIT ' . $limit[0] . ', ' . $limit[1] . ' ';
            }
        }

        return $limits;
    }

    private function parseUnion($data)
    {
        return '';
    }

    private function parseLock($data)
    {
        return '';
    }

    private function parseComment($data)
    {
        return '';
    }

    private function parseForce($data)
    {
        return '';
    }

    /**
     * 生成删除SQL
     */
    public function delete()
    {
        //
    }

    /**
     * 生成更新SQL
     */
    public function update()
    {
        //
    }

    /**
     * 生成单个添加SQL
     */
    public function insert()
    {
        //
    }

    /**
     * 生成批量添加SQL
     */
    public function insertAll()
    {
        //
    }
}