<?php
include WEB_PATH.'lib/smarty/smarty.php';
class CommonAct {
	var $smarty;
	public function __construct() {
		$this->smarty = new AppSmarty;
	}
}