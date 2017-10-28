<?php
	class HomeController {
		public function __construct() {
		}
		
		public function index() {
			$blogModel = new BlogModel();
			$classifyModel = new ClassifyModel();
			$lists = $blogModel->getBlogLists(0,30,'id desc');
			foreach ($lists as $key => $value) {
				$lists[$key]['year'] = substr($value['createtime'],0,4);
				$lists[$key]['month'] = substr($value['createtime'], 5, 5);
				$classify = $classifyModel->getInfoById($value['classify_id']);
				$lists[$key]['classify_name'] = $classify['name']; 

			}
			
			include "./view/home/index.html";
		}

		public function blogInfo () {
			$id = $_GET['id'];
			if (!$id) {
				die('error');
			}
			$blogModel = new BlogModel();
			$commentModel = new CommentModel();
			$userModel = new UserModel();
			$blogInfo = $blogModel->getInfoById($id);
			$blogInfo['createdate'] = substr($blogInfo['createtime'],0, 10);
			$blogInfo['createdate'] = date('Y/m/d', strtotime($blogInfo['createtime']));

			$where = "classify_id = {$blogInfo['classify_id']} and id != {$id}";
			$relation = $blogModel->getBlogLists(0, 10,'id asc',$where);

			$commentWhere = "blog_id = {$id}";
			$commentLists = $commentModel->getLists(0, 20,'id asc', $commentWhere);
			foreach($commentLists as $key=>$comment) {
				$userInfo = $userModel->getUserInfoById($comment['user_id']);
				$commentLists[$key]['author'] = $userInfo;
			}

			$commentCount = $commentModel->getCount($commentWhere);
			
			include "./view/home/bloginfo.html";
		}

		public function doComment() {
			$blog_id = $_POST['blog_id'];
			$content = $_POST['content'];
			$user_id = $_SESSION['me']['id'];
			$parent_id  = isset($_POST['parent_id']) ? $_POST['parent_id'] : 0;

			if (!isset($_SESSION['me']['id']) || !$_SESSION['me']['id']) {
				header('Location:index.php?c=UserCenter&a=login');
				echo '请登录';
				die();
			}

			if(!$blog_id || !$content) {
				die('参数错误');
			}
			$commentModel = new CommentModel();
			$status = $commentModel->add($blog_id, $user_id, $parent_id, $content);
			if ($status) {
				header('Location:index.php?c=Home&a=blogInfo&id='.$blog_id);
				echo '评论成功，1秒后跳转到list';
				die();
				// header('Refresh:0,Url=index.php?c=Home&a=blogInfo&id='.$blog_id);
				// echo '评论成功，1秒后跳转到list';
				// die();
			} else {
				die('error');
			}

		}

		public function study() {
			$classify_id = $_GET['classify_id'];
			$where = '1';
			if ($classify_id) {
				$where .= " and classify_id in ({$classify_id})";
			} else {
				$where .= " and classify_id in (4,5,6)";
			}

			$where .= " and status = 1";
			
			$blogModel = new BlogModel();
			$classifyModel = new ClassifyModel();

			
			$lists = $blogModel->getBlogLists(0,30,'id desc', $where);
			foreach ($lists as $key => $value) {
				$lists[$key]['year'] = substr($value['createtime'],0,4);
				$lists[$key]['month'] = substr($value['createtime'], 5, 5);
				$classify = $classifyModel->getInfoById($value['classify_id']);
				$lists[$key]['classify_name'] = $classify['name']; 

			}

			$classify = $classifyModel->getLists(2);

			include "./view/home/study.html";
		}
		public function lianxi(){
			$classifyModel = new ClassifyModel();
			$data = $classifyModel->lianxi();
			include "./1/lists.html";
		}

	}