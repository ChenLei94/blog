<?php
class ClassifyModel {
	public $mysqli;
	function __construct() {
		$this->mysqli = new mysqli("127.0.0.1","root","","blog");
		$this->mysqli->query('set names utf8');
	}
	public function add($name, $parent_id=0) {
		$sql = "insert into classify(name, parent_id) values ('{$name}', {$parent_id})";
		echo $sql;
		return $this->mysqli->query($sql);
	}
	function getLists($pid=0) {
		$sql = "select * from classify where parent_id = {$pid}";
		$res = $this->mysqli->query($sql);
		$data = $res->fetch_all(MYSQL_ASSOC);
		foreach ($data as $key => $value) {
			$sqlChild = "select * from classify where parent_id = {$value['id']}";
			$resChild = $this->mysqli->query($sqlChild);
			$child = $resChild->fetch_all(MYSQL_ASSOC);
			$data[$key]['child'] = $child;
		}
		return $data;
	}
	public function getInfoById($id) {
		$sql = "select * from classify where id = {$id}";
		$res = $this->mysqli->query($sql);
		$data = $res->fetch_all(MYSQL_ASSOC);
		return isset($data[0]) ? $data[0] : array();
	}
	public function getInfoByName($name) {
		$sql = "select * from classify where name = '{$name}'";
		$res = $this->mysqli->query($sql);
		$data = $res->fetch_all(MYSQL_ASSOC);
		return isset($data[0]) ? $data[0] : array();
	}
	public function getClassifyLists() {
		$sql = "select * from classify";
		$res = $this->mysqli->query($sql);
		$data = $res->fetch_all(MYSQL_ASSOC);
		return $data;
	}
	public function audit($id, $status=0) {
		$sql = "update classify set status = {$status} where id = {$id}";
		return $this->mysqli->query($sql);
	}
	public function edit($id,$name,$pid){
		$sql = "update classify set name = '{$name}',parent_id = {$pid} where id = {$id}";
		return $this->mysqli->query($sql);
	}
	// function lianxi() {
	// 	$sql = "select * from classify where parent_id = 0";
	// 	$res = $this->mysqli->query($sql);
	// 	$data = $res->fetch_all(MYSQL_ASSOC);
	// 	foreach ($data as $key => $value) {
	// 		$sqli = "insert into option (name) value('$value['name']')";
	// 		$resi = $this->mysqli->query($sqli);
	// 	}
	// 	return $data;
	// }
}