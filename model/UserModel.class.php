<?php
	class UserModel {
		public $mysqli;
		function __construct() {
			$this->mysqli = new mysqli("127.0.0.1","root","","blog");
			$this->mysqli->query('set names utf8');
		}
		function addUser($name , $email, $password, $image) {
			$date = date('Y-m-d H:i:s');
			$sql = "insert into user(name,email,password, image, createtime) value ('{$name}', '{$email}', '{$password}', '{$image}', '{$date}')";
			$res = $this->mysqli->query($sql);
			return $res;
		}	
		public function getUserLists() {
			$sql = "select * from user";
			$res = $this->mysqli->query($sql);
			$data = $res->fetch_all(MYSQL_ASSOC);
			return $data;
		}
		function getUserInfoById($id) {
			$sql = "select * from user where id = {$id}";
			$res = $this->mysqli->query($sql);
			$data = $res->fetch_all(MYSQL_ASSOC);
			return isset($data[0]) ? $data[0] : array();
		}
		function getUserInfoByEmail($email) {
			$sql = "select * from user where email = '{$email}'";
			$res = $this->mysqli->query($sql);
			$data = $res->fetch_all(MYSQL_ASSOC);
			return isset($data[0]) ? $data[0] : array();
		}
		public function audit($id, $status=0) {
			$sql = "update user set status = {$status} where id = {$id}";
			return $this->mysqli->query($sql);
		}
		public function edit($id,$data){
			$time = date('Y-m-d H:i:s');
			$sql = "update user set name = '{$data['name']}',email = '{$data['email']}',password='{$data['password']}',image='{$data['image']}',updatetime='{$time}' where id = {$id}";
			return $this->mysqli->query($sql);
		}
	}