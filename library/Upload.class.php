<?php
class Upload {
	private $ext;
	private $fileInfo;
	public function run($name) {
		$res = array('status'=>'ok', 'msg'=>"ok");
		$path = "./public/upload/";
		$this->fileInfo = $_FILES[$name];
		if (!$this->checkType($_FILES[$name]["type"])) {
			$res = array('status'=>'error', 'msg'=>"type error");
			//header('Refresh:3,Url=index.php?c=UserCenter&a=reg');
			return $res;
		}
		if (!$this->checkSize($_FILES[$name]["size"])) {
			$res = array('status'=>'error', 'msg'=>"size error");
			//header('Refresh:3,Url=index.php?c=UserCenter&a=reg');
			return $res;
		}
		$ext = $this->getExt($_FILES[$name]["name"]);
		$fileName = 'img_'.time().rand(1,1000000) . $ext;	
		$fileName = $path . $fileName;		
		move_uploaded_file($_FILES[$name]["tmp_name"], $fileName);
		$res = array('status'=>'ok', 'msg'=>"ok", 'filename' => $fileName);
		return $res;
	}

	public function checkType($type) {
		$base = array('image/png','image/x-png','image/jpeg', 'image/jpg', 'image/gif');
		if (in_array($type, $base)) {
			return true;
		} 
		return false;
	}

	public function checkSize($size) {
		if ($size <= 200000){
			return true;
		}
		return false;
	}


	public function getExt($name) {
		$pos = strrpos($name, '.');
		$ext = substr($name, $pos);
		$this->ext = $ext;
		return $ext;
	}
	public function returnExt() {
		return $this->ext;
	}
	public function returnSize() {
		return $this->fileInfo['size'];
	}
}