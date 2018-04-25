<?php

namespace wangben\db\service;

class Mysql
{
    public static $instance = [];
    public static $connect  = [];

    public function __construct($config)
    {
        try {

            $db  = new \PDO('mysql:host=' . $config['hostname'] . ';port=' . $config['hostport'] . ';dbname=' . $config['database'], $config['username'], $config['password']);
            $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $db->exec('set names ' . $config['charset']);
            self::$connect = $db;

        } catch (\Exception $e) {

            var_dump($e);

        }

        return true;
    }

    /**
     * 获取PDO实例
     */
    public static function getInstance($config = [])
    {
        $config_id = md5(var_export($config, true));

        if (!isset(self::$instance[$config_id])) {

            $config = self::databaseConfig($config);

            self::$instance[$config_id] = new self($config);

        }

        return self::$instance[$config_id];
    }

    /**
     * Config处理
     */
    public static function databaseConfig($config = [])
    {
        $config['hostname'] = isset($config['hostname']) ? ($config['hostname'] ?: '') : '';
        $config['database'] = isset($config['database']) ? ($config['database'] ?: '') : '';
        $config['username'] = isset($config['username']) ? ($config['username'] ?: '') : '';
        $config['password'] = isset($config['password']) ? ($config['password'] ?: '') : '';
        $config['hostport'] = isset($config['hostport']) ? ($config['hostport'] ?: 3306) : 3306;
        $config['charset']  = isset($config['charset']) ? ($config['charset'] ?: 'utf8') : 'utf8';

        return $config;
    }
}