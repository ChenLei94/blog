<?php
class UserCenterController {
	public function reg () {
		include "./view/usercenter/reg.html";
	}
	public function reg1 () {
		include "./view/usercenter/reg1.html";
	}
	public function doReg() {
		$name 	= $_POST['name'];
		$email 	= $_POST['email'];
		$password = $_POST['password'];
		$upload = L("Upload");
		$uploadRes = $upload->run('image');
		if ($uploadRes['status'] == 'error') {
			die($uploadRes['msg']);
		}
		$image = $uploadRes['filename'];
		if (empty($email) || empty($password)) {
			header('Refresh:1,Url=index.php?c=UserCenter&a=reg');
			echo '注册不成功';
			die();
		}
		$userModel = new UserModel();
		$emailStatus = $userModel->getUserInfoByEmail($email);
		if (is_array($emailStatus) && !empty($emailStatus)) {
			header('Refresh:1,Url=index.php?c=UserCenter&a=reg');
			echo '注册不成功,邮箱存在';
			die();
		}
		$status = $userModel->addUser($name , $email, $password, $image);
		if ($status) {
			header('Refresh:1,Url=index.php?c=UserCenter&a=login');
			echo '注册成功，1秒后跳转到login';
			die();
		} else {
			header('Refresh:3,Url=index.php?c=UserCenter&a=reg');
			echo '注册失败，1秒后跳转到reg';
			die();
		}
	}
	public function login() {
		include "./view/usercenter/login.html";
	}
	public function doLogin() {
		$email = $_POST['email'];
		$password = $_POST['password'];
		if (!$email || !$password) {
			header('Refresh:3,Url=index.php?c=UserCenter&a=login');
			echo '必填信息，登录不成功';
			die();
		}
		$verifyCode = $_POST['verify'];
		if ($verifyCode != $_SESSION['verifyCode']) {
			header('Refresh:3,Url=index.php?c=UserCenter&a=login');
			echo '验证码错误，登录不成功';
			die();
		}
		$userModel =  new UserModel();
		$userInfo = $userModel->getUserInfoByEmail($email);
		if ($userInfo['password'] == $password) {
			unset($userInfo['password']); 
			$_SESSION['me'] = $userInfo;
			header('Refresh:3,Url=index.php?c=Home&a=index');
			echo '登录成功';
			die();
		} else {
			header('Refresh:3,Url=index.php?c=UserCenter&a=login');
			echo '登录不成功';
			die();
		}
	}
	public function logout () {
		unset($_SESSION['me']);
		header('Refresh:3,Url=index.php?c=UserCenter&a=login');
		echo 'logout';
		die();
	}
	public function verifyCode() {
		header("Content-Type:image/png");
		$img = imagecreate(50, 25);
		$back = imagecolorallocate($img, 0xFF, 0xFF, 0xFF);
		$red = imagecolorallocate($img, 255, 0, 0);
		$str = getRandom(4) ;
		$_SESSION['verifyCode'] = $str;
		imagestring($img, 5, 7, 5, $str, $red);
		imagepng($img);
		imagedestroy($img);
	}	
}