<?php
class AuthAct extends CommonAct {
	public function __construction() {}

	public function act_authIndex() {
		print_r($_REQUEST);exit;
		$this->smarty->display('auth.tpl');
	}
}