<?php
//单入口
header("Content-type: text/html; charset=utf-8");
//获取控制器名 
$controller = isset($_GET['c']) ? $_GET['c'] : 'Home';
//获取方法名
$action 	= isset($_GET['a']) ? $_GET['a'] : 'index';
session_start();
include "./common/function.php";
//拼类名
$className = "{$controller}Controller";  //UserController 控制器
//实例化
$con = new $className();
//调用
$con->$action();