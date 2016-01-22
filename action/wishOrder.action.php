<?php
class WishOrderAct extends CommonAct{
	public function __construct() {
		parent::__construct();
		$this->smarty->template_dir = WEB_PATH.'html/templates/order';	//设置本功能所有页面的模板路径
	}

	/**
	 * 获取线上订单
	 */
	public function act_wishOrderList() {
		$wishOrderApi	= new WishOrderApi('geshan0728', 1);
		$ret			= $wishOrderApi->getAllorder(0, 50);
		print_r($ret);
		/*$listingData = wishOrderModel::getOrderData();
		$this->smarty->assign('data', $listingData[0]);
		$this->smarty->display('wishListinglist.tpl');*/
	}
}