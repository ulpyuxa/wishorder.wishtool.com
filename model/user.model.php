<?php
class UserModel {
	public static $dbConn;
	static $errCode	=	0;
	static $errMsg	=	"";

	public function __construct() {
	}
	
	public static function initDB() {
		global $dbConn;
		self::$dbConn = $dbConn;
	}

	public function login() {
		self::initDB();
		
		$errStr = '';
		$user	= isset($_REQUEST['email']) && strlen($_REQUEST['email']) > 4 ? $_REQUEST['email'] : '';
		$pwd	= isset($_REQUEST['pwd']) && strlen($_REQUEST['pwd']) > 4 ? md5($_REQUEST['pwd']) : '';
		if(empty(trim($errStr))) {
			$errStr .= '请重新输入用户名!';
		}
		if(empty(trim($pwd))) {
			$errStr .= '请重新输入密码!';
		}
		if(empty(trim($errStr))) {
			self::$errCode	= '1301';
			self::$errMsg	= $errStr;
			return false;
		}

		$sql	= 'select * from ws_user where userName="'.$user.'" and password = "'.$pwd.'"';
		$query	= self::$dbConn->query($sql);
		return self::$dbConn->fetch_array_all($query);
	}
}