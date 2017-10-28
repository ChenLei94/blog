<?php
class BlogController {
	public function __construct() {
	}
	public function add () {
		$classifyModel = new ClassifyModel();
		$classify = $classifyModel->getLists();
		include "./view/blog/add.html";
	}
	public function doAdd() {
		$upload = L("Upload");
		$uploadRes = $upload->run('image');
		if ($uploadRes['status'] == 'error') {
			die($uploadRes['msg']);
		}
		$filename = $uploadRes['filename'];
		$content = $_POST['content'];
		$classify = $_POST['classify'];
		$title = $_POST['title'];
		$data = array(
			'content' 	=> $content,
			'classify' 	=> $classify,
			'title' 	=> $title,
			'image' 	=> $filename,
			);
		$blogModel = new BlogModel();
		$status = $blogModel->addBlog($data);
		if ($status) {
			header('Refresh:1,Url=index.php?c=Blog&a=lists');
			echo '发布成功，1秒后跳转到list';
			die();
		}
	}
	public function image () {
		include "./view/blog/image.html";
	}
	public function doImage() {
		include "./library/Upload.class.php";
		$upload = new Upload();
		$filename = $upload->run('photo');
		echo $filename;
		echo $upload->returnSize();
	}
	public function lists() {
		$blogModel = new BlogModel();
		$classifyModel = new ClassifyModel();
		$p = isset($_GET['p']) ? $_GET['p'] : 1;
		$pageNum =  3; 
		$offset = ($p - 1) * $pageNum;
		$count = $blogModel->getBlogCount();
		$allPage = ceil($count/$pageNum);
		$data = $blogModel->getBlogLists($offset, $pageNum);
		foreach ($data as $key => $value) {
			$classify = $classifyModel->getInfoById($value['classify_id']);
			$data[$key]['classify_name'] = isset($classify['name']) ? $classify['name'] : '';
		}
		include "./view/blog/lists.html";
	}
	public function online() {
		$id = $_GET['id'];
		$blogModel = new BlogModel();
		$data = $blogModel->audit($id, 1);
		echo $data;
		die();
	}
	public function offline() {
		$id = $_GET['id'];
		$blogModel = new BlogModel();
		$data = $blogModel->audit($id, 0);
		echo $data;
		die();
	}
	public function edit () {
		$id = $_GET['id'];
		$classifyModel = new ClassifyModel();
		$blogModel = new BlogModel();
		$data = $blogModel->getInfoById($id);
		$classify = $classifyModel->getLists();
		if (empty($data)) {
			die('error');
		}
		include "./view/blog/edit.html";
	}
	public function doEdit() {
		$id = $_POST['id'];
		if (!$id) {
				die('请求错误');
		}
		$filename = $_POST['image'];
		if (!empty($_FILES['image']['name'])) {
			$upload = L("Upload");
			$uploadRes = $upload->run('image');
			if ($uploadRes['status'] == 'error') {
				die($uploadRes['msg']);
			}
			$filename = $uploadRes['filename'];
		}
		$content 	= $_POST['content'];
		$classify 	= $_POST['classify'];
		$title 		= $_POST['title'];
		$data = array(
			'content' 	=> $content,
			'classify' 	=> $classify,
			'title' 	=> $title,
			'image' 	=> $filename,
			);
		$blogModel = new BlogModel();
		$status = $blogModel->editBlog($data, $id);
		if ($status) {
			header('Refresh:1,Url=index.php?c=Blog&a=lists');
			echo '发布成功，1秒后跳转到list';
			die();
		}
	}
}