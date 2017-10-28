<?php
class ClassifyController {
	public function __construct() {
	}
	public function add () {
		$classifyModel = new ClassifyModel();
		$classify = $classifyModel->getLists();	
		include "./view/classify/add.html";
	}
	public function doAdd() {
		$pid = $_POST['pid'];
		$name = $_POST['name'];
		if (!$name) {
			die('error');
		}
		$classifyModel = new ClassifyModel();
		$nameStatus = $classifyModel->getInfoByName($name);
		if (is_array($nameStatus) && !empty($nameStatus)) {
			header('Refresh:1,Url=index.php?c=Classify&a=add');
			echo '注册失败,分类已存在';
			die();
		}
		$classifyModel = new ClassifyModel();
		$status = $classifyModel->add($name,$pid);
		if ($status) {
			header('Refresh:1,Url=index.php?c=Classify&a=lists');
			die();
		} else {
			header('Refresh:1,Url=index.php?c=Classify&a=add');
			die('error');
		}
	}
	public function lists() {
		$classifyModel = new ClassifyModel();
		$data = $classifyModel->getClassifyLists();
		include "./view/classify/lists.html";
	}
	public function online() {
		$id = $_GET['id'];
		$classifyModel = new ClassifyModel();
		$data = $classifyModel->audit($id, 1);
		echo $data;
		header('Refresh:1,Url=index.php?c=Classify&a=lists');
		die();
	}
	public function offline() {
		$id = $_GET['id'];
		$classifyModel = new ClassifyModel();
		$data = $classifyModel->audit($id, 0);
		echo $data;
		header('Refresh:1,Url=index.php?c=Classify&a=lists');
		die();
	}
	public function edit() {
		$id = $_GET['id'];
		$classifyModel = new ClassifyModel();
		$data = $classifyModel->getInfoById($id);
		$classify = $classifyModel->getLists();
		if (empty($data)) {
			die('error');
		}
		include "./view/classify/edit.html";
	}
	public function doedit() {
		$id = $_POST['id'];
		$name = $_POST['name'];
		$pid = $_POST['pid'];
		if (!$name) {
			die('error');
		}
		// $classifyModel = new ClassifyModel();
		// $nameStatus = $classifyModel->getInfoByName($name);
		// if (is_array($nameStatus) && !empty($nameStatus)) {
		// 	header('Refresh:1,Url=index.php?c=Classify&a=add');
		// 	echo '修改失败,分类已存在';
		// 	die();
		// }
		$classifyModel = new ClassifyModel();
		$status = $classifyModel->edit($id,$name,$pid);
		if ($status) {
			header('Refresh:1,Url=index.php?c=Classify&a=lists');
			die('OK');
		} else {
			header('Refresh:1,Url=index.php?c=Classify&a=add');
			die('error');
		}
	}
}