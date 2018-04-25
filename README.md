#使用方法

<?php

namespace app\index\controller;

use wangben\db\service\DB;

class Index
{
    public function index()
    {
        $info = DB::table('user')->field('id')->where('id', '=', 1)->find();

        //$info = \wangben\db\service\DB::table('user')->field('id')->where('id', '=', 1)->find();

        echo '<pre>';
        print_r($info);
    }
}