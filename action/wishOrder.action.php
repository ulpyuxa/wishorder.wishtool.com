<?php
class WishOrderAct extends CommonAct{
	public function __construct() {
		parent::__construct();
		$this->smarty->template_dir = WEB_PATH.'html/templates/order';	//设置本功能所有页面的模板路径
	}

	/**
	 * 获取线上订单
	 */
	public function act_wishOrderSync($start = 0, $count = 50) {
		$wishOrderApi	= new WishOrderApi('geshan0728', 1);
		$ret			= $wishOrderApi->getAllorder($start, $count);
		$insert			= WishOrderModel::addOrder($ret);
		return $insert;
	}

	/**
	 * 订单列表
	 */
	public function act_wishOrderList() {
		$orderData	= wishOrderModel::getOrderData();

		$this->smarty->assign('data', $orderData);
		$this->smarty->display('wishOrderList.tpl');
	}
}