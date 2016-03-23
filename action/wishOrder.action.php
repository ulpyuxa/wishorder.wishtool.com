<?php
class WishOrderAct extends CommonAct{
	static $errCode	=	0;
	static $errMsg	=	"";

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
		if(!$insert) {
			self::$errCode	= WishOrderModel::$errCode;
			self::$errMsg	= WishOrderModel::$errMsg;
			return false;
		}
		return true;
	}

	/**
	 * 订单列表
	 */
	public function act_wishOrderList() {
		$orderData	= WishOrderModel::getOrderData();
		$orderCount	= WishOrderModel::orderCount();
		$postMethod	= WishOrderModel::getPostList();
		$this->smarty->assign('orderData', $orderData);
		$this->smarty->assign('orderCount', $orderCount);
		$this->smarty->assign('postMethod', $postMethod);
		$this->smarty->display('wishOrderList.tpl');
	}

	/**
	 * 功能：上传跟踪号
	 */
	public function act_fulfillOrder() {
		$uploadStatus	= WishOrderModel::fulfillOrder();
		if(!$uploadStatus) {
			self::$errCode	= WishOrderModel::$errCode;
			self::$errMsg	= WishOrderModel::$errMsg;
			return false;
		}
		return $uploadStatus;
	}
}