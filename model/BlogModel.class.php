<?php
	class BlogModel {

		public $mysqli;
		function __construct() {
			$this->mysqli = new mysqli("127.0.0.1","root","","blog");
			$this->mysqli->query('set names utf8');
		}

		function addBlog($data) {
			//$time = time();
			$time = date('Y-m-d H:i:s');
			$sql = "insert into blog(content,image,title,classify_id,createtime) value ('{$data['content']}', '{$data['image']}','{$data['title']}', '{$data['classify']}','{$time}')";
			$res = $this->mysqli->query($sql);
			return $res;
		}

		function getBlogLists($offset=0, $limit=20,$order='id asc',$where='1') {
			$sql = "select * from blog where {$where} order by {$order} limit {$offset},{$limit}";
			$res = $this->mysqli->query($sql);
			$data = $res->fetch_all(MYSQL_ASSOC);
			return $data;
		}

		function getBlogCount () {
			$sql = "select count(*) as num from blog";
			$res = $this->mysqli->query($sql);
			$data = $res->fetch_all(MYSQL_ASSOC);
			return $data[0]['num'];
		}

		public function audit($id, $status=0) {
			$sql = "update blog set status = {$status} where id = {$id}";
			return $this->mysqli->query($sql);
		}

		public function getInfoById($id) {
			$sql = "select * from blog where id = {$id}";
			$res = $this->mysqli->query($sql);
			$data = $res->fetch_all(MYSQL_ASSOC);
			return isset($data[0]) ? $data[0] : array();
		}
		public function editBlog($data, $id) {
			$time = date('Y-m-d H:i:s');
			$sql = "update blog set content = '{$data['content']}', title = '{$data['title']}', classify_id = {$data['classify']}, image = '{$data['image']}', updatetime = '{$time}' where id = {$id}";
			return $this->mysqli->query($sql);
		}
	}