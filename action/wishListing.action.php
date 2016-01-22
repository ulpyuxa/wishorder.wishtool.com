<?php
class WishListingAct extends CommonAct{
	public function __construct() {
		parent::__construct();
		$this->smarty->template_dir = WEB_PATH.'html/templates/listing';	//设置本功能所有页面的模板路径
	}
	public function act_wishListingList() {
		$listingData = AliexListingModel::getListingData();
		$this->smarty->assign('data', $listingData[0]);
		$this->smarty->display('wishListinglist.tpl');
	}
}