<?php
include WEB_PATH.'lib/smarty/smarty.php';
class CommonAct {
	var $smarty;
	public function __construct() {
		$this->smarty = new AppSmarty;
		self::_checkLogin();	//检查登录情况
	}

	/**
	 * 功能: 检测是否登录
	 */
	public function _checkLogin() {
		if(!isset($_COOKIE['USERINFO'])) {
			if(strtolower($_REQUEST['act']) != 'index' && strtolower($_REQUEST['mod']) != 'login') {
				//echo $_REQUEST['act'], '    ', $_REQUEST['mod'];exit;
				header('Location: /index.php?mod=login&act=index');
			} else {
				return true;
			}
		}
	}
}