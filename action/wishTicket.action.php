<?php
class WishProductAct extends CommonAct{
	static $errCode	=	0;
	static $errMsg	=	"";

	public function __construct() {
		parent::__construct();
		$this->smarty->template_dir = WEB_PATH.'html/templates/ticket';	//设置本功能所有页面的模板路径
	}

	public function syncTicket() {
		
	}
}