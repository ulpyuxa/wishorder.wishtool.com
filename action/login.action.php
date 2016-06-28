<?php
class LoginAct extends CommonAct {
	public function __construct() {
		parent::__construct();
	}

	/**
	 * 登陆页展示
	 */
	public function act_index() {
		if(isset($_COOKIE['USERINFO'])) {
			header('Location: /index.php?mod=wishProduct&act=wishProductList&isOnline=online');
			return true;
		}
		$this->smarty->display('login.tpl');
	}

	public function act_login() {
		if(isset($_COOKIE['USERINFO'])) {
			header('Location: /index.php?mod=wishProduct&act=wishProductList&isOnline=online');
		}
		$userInfo = UserModel::login();
		if(!empty($userInfo)) {
			$hostInfo = explode('.', $_SERVER['SERVER_NAME']);
			unset($hostInfo[0]);
			setcookie('USERINFO', json_encode($userInfo), 0, "/", ".".implode('.', $hostInfo));
			header('Location: /index.php?mod=wishProduct&act=wishProductList&isOnline=online');
			return true;
		}
		header('Location: /index.php?mod=login&act=index');
	}
}