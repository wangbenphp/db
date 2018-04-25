<?php

namespace wangben\db\service;

class Query
{
    //查询参数
	protected $options    = [];
	//PDO连接
	protected $connect    = [];
	//生成SQL语句
    protected $builder    = [];
	//Config配置
	protected $config     = [];
	//数据处理
    protected $datahandle = [];

	/**
	 * 连接PDO、创建MySQL语句、数据处理
	 */
	public function __construct($config)
    {
        $this->config     = $config;

        if (empty($this->connect)) {
            $mysql   = \wangben\db\service\Mysql::getInstance($config);
            $this->connect    = $mysql::$connect;
        }

        if (empty($this->builder)) {
            $this->builder    = new Builder();
        }

        if (empty($this->datahandle)) {
            $this->datahandle = new DataHandle();
        }
    }

    /**
	 * 指定当前操作的数据表
	 */
	public function table($name)
    {
        $this->options['table'] = $name;
        return $this;
    }

    /**
     * 指定默认的数据表名（不含前缀）
     */
    public function name($name)
    {
        $this->options['table'] = $this->config['prefix'] . $name;
        return $this;
    }

    /**
     * 指定数据表别名
     */
    public function alias($name)
    {
        $this->options['alias'] = $name;
        return $this;
    }

    /**
     * 查询字段
     */
    public function field($data)
    {
        $this->options['field'] = $data;
        return $this;
    }

    /**
     * 指定AND查询条件
     */
    public function where($field, $op = null, $condition = null)
    {
        $param = func_get_args();
        $this->disposeWhere('AND', $field, $op, $condition, $param);
        return $this;
    }

    /**
     * 指定OR查询条件
     */
    public function whereOr($field, $op = null, $condition = null)
    {
        $param = func_get_args();
        $this->disposeWhere('OR', $field, $op, $condition, $param);
        return $this;
    }

    /**
     * Order
     */
    public function order($data1, $data2 = 'desc')
    {
        if (is_array($data1)) {
            foreach ($data1 as $k => $v) {
                if (is_int($k)) {
                    $this->options['order'][$v] = 'desc';
                } else {
                    $this->options['order'][$k] = $v;
                }
            }
        } else if (is_string($data1)) {
            $this->options['order'][$data1] = $data2;
        }

        return $this;
    }

    public function limit($offset, $length = null)
    {
        if (is_null($length)) {
            $this->options['limit'] = [0, $offset];
        } else {
            $this->options['limit'] = [$offset, $length];
        }
        return $this;
    }

    /**
     * 查找单条记录
     */
    public function find()
    {
        $this->limit(1);
        $sql    = $this->builder->select($this->options);
        $res    = $this->query($sql);
        $result = $this->datahandle->select($res, 1);

        return $result;
    }

    /**
     * 查询所有记录
     */
    public function select()
    {
        $sql    = $this->builder->select($this->options);
        $res    = $this->query($sql);
        $result = $this->datahandle->select($res);

        return $result;
    }

    public function count($field = '*')
    {
        if (isset($this->options['group'])) {
            //
        }

        return $this->value();
    }

    /**
     * 得到某个字段的值
     */
    public function value()
    {
        //
    }

    //

    /**
     * 处理Where查询条件
     */
    private function disposeWhere($type, $field, $op, $condition, $param)
    {
        $count = count($param);
        if ($count == 2) {
            $sign  = '=';
            $value = $op;
        } else if ($count == 3) {
            $sign  = $op;
            $value = $condition;
        } else {
            echo 'WHERE条件有误';exit;
        }

        $this->options['where'][$type][$field][] = [$sign, $value];
    }

    /**
     * PDO的query处理
     */
    private function query($sql)
    {
        $res = $this->connect->query($sql);
        return $res;
    }
}