<?php

namespace wangben\db\service;

use wangben\db\service\Mysql;

class DB
{
	protected static $instance = [];

	public static function connect($config = [], $name = false)
	{
		$config_id = md5(var_export($config, true));

		if ($name === true || !isset(self::$instance[$config_id])) {
			
			$config = self::databaseConfig($config);

			self::$instance[$config_id] = Mysql($config);

		}

		return self::$instance[$config_id];
	}

    /*
     * 获取配置文件
     */
	private static function databaseConfig($config = [])
	{
		if (empty($config)) {
			$config = require dirname(__DIR__) . '/library/Config.php';
		}
		
		return $config;
	}

    /*
     * 静态调用驱动类的方法
     */
	public static function __callStatic($method, $param)
	{
		return call_user_func_array([self::connect(), $method], $param);
	}
}