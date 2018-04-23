<?php

require 'DB.php';

$DB  = new DB();

$res = $DB::table('test');

print_r($res);