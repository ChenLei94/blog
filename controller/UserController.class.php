<?php
	class UserController {
		public function lists() {
			$userModel = new UserModel();
			$data = $userModel->getUserLists();
			include "./view/user/lists.html";
		}
		public function online() {
		$id = $_GET['id'];
		$UserModel = new UserModel();
		$data = $UserModel->audit($id, 1);
		echo $data;
		header('Refresh:1,Url=index.php?c=User&a=lists');
		die();
	}
	public function offline() {
		$id = $_GET['id'];
		$UserModel = new UserModel();
		$data = $UserModel->audit($id, 0);
		echo $data;
		header('Refresh:1,Url=index.php?c=User&a=lists');
		die();
	}
	public function edit() {
		$id = $_GET['id'];
		$userModel = new UserModel();
		$data = $userModel->getUserInfoById($id);
		if (empty($data)) {
			die('error');
		}
		include "./view/user/edit.html";
	}
	public function doedit() {
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
		$name = $_POST['name'];
		$email = $_POST['email'];
		$password = $_POST['password'];	
		$data = array(
			'name' 	=> $name,
			'email' 	=> $email,
			'password' 	=> $password,
			'image' 	=> $filename,
			);
	
		$userModel = new UserModel();
		$status = $userModel->edit($id,$data);
		if ($status) {
			header('Refresh:1,Url=index.php?c=user&a=lists');
			die('OK');
		} else {
			header('Refresh:1,Url=index.php?c=user&a=add');
			die('error');
		}
	}

	}