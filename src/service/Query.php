<?php

require 'DB.php';

class Query
{
	// 数据库Connection对象实例
    protected $connection;

	public function __construct($connect = null)
	{
		print_r($connect);exit;
		//$this->connection = $connection ?: new DB()::connection([], true);
	}
}