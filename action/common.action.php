<?php
include WEB_PATH.'lib/smarty_sae/smarty.php';
class CommonAct {
	var $smarty;
	public function __construct() {
		$this->smarty = new AppSmarty;
	}
}