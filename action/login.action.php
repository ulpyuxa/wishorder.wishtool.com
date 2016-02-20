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
			header('Location: /index.php?mod=wishProduct&act=wishProductList');
			return true;
		}
		$this->smarty->display('login.tpl');
	}

	public function act_login() {
		if(isset($_COOKIE['USERINFO'])) {
			header('Location: /index.php?mod=wishProduct&act=wishProductList');
		}
		$userInfo = UserModel::login();
		if(!empty($userInfo)) {
			print_r($_SERVER);exit;
			$hostInfo = explode('.', $_SERVER['HTTP_HOST']);
			unset($hostInfo[0]);
			echo ".".implode('.', $hostInfo);exit;
			setcookie('USERINFO', json_encode($userInfo), time()+1800, "/", ".".implode('.', $hostInfo));
			header('Location: /index.php?mod=wishProduct&act=wishProductList');
			return true;
		}
		header('Location: /index.php?mod=login&act=index');
	}
}