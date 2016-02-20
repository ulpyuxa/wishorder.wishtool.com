<?php
class LoginAct extends CommonAct {
	public function __construct() {
		parent::__construct();
	}

	/**
	 * 登陆页展示
	 */
	public function act_index() {
		if(isset($_COOKIE['USERINFO']) || isset($_COOKIE['USERINFO1'])) {
			header('Location: /index.php?mod=wishProduct&act=wishProductList');
			return true;
		}
		$this->smarty->display('login.tpl');
	}

	public function act_login() {
		if(isset($_COOKIE['USERINFO']) || isset($_COOKIE['USERINFO1'])) {
			header('Location: /index.php?mod=wishProduct&act=wishProductList');
		}
		$userInfo = UserModel::login();
		if(!empty($userInfo)) {
			setcookie('USERINFO', json_encode($userInfo), time()+1800, "/", ".wishtool.cn");
			setcookie('USERINFO1', json_encode($userInfo), time()+1800, "/", ".gicp.net");
			header('Location: /index.php?mod=wishProduct&act=wishProductList');
			return true;
		}
		header('Location: /index.php?mod=login&act=index');
	}
}