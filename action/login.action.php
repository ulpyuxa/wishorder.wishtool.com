<?php
class LoginAct extends CommonAct {
	public function act_index() {
		$this->smarty->display('login.tpl');
	}
}