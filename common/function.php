<?php
//自动加载
function __autoload($class) {
	if (strpos($class, "Controller") !== false) {
		$dir = 'controller';
	} elseif (strpos($class, "Model") !== false) {
		$dir = 'model';
	} else {
		die($class."not exist");
	}
	include "./{$dir}/{$class}.class.php";
}
//实例化$name
function L ($name) {
	include "./library/{$name}.class.php";
	$obj = new $name();
	return $obj;
}	
//生成随机验证码
function getRandom($len) {
	$base = "1234567890";
	$max = strlen($base);
	mt_srand();
	$res = '';
	for($i=0;$i<$len;$i++) {
		$res .= $base[mt_rand(0,$max-1)];
	}
	return $res;
}