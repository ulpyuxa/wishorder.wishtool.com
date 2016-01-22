<?php
class WishOrderModel {
	public static $dbConn;
	static $errCode	=	0;
	static $errMsg	=	"";

	public function __construct() {
	}
	
	public static function initDB() {
		global $dbConn;
		self::$dbConn = $dbConn;
	}

	public static function getOrderData() {
		self::initDB();
		$sql	= 'SELECT * FROM `ws_order`';
		$query	= self::$dbConn->query($sql);
		$ret	= self::$dbConn->fetch_array_all($query);
		return $ret;
	}

	/** 
	 * 根据订单ID查询订单
	 */
	public static function getOrderDataById($ids = array()) {
		self::initDB();
		$sql	= 'select * from ws_order where order_id in ("'.implode('","', $ids).'")';
		$query	= self::$dbConn->query($sql);
		$ret	= self::$dbConn->fetch_array_all($query);
		if(!empty($ret)) {
			foreach($ret as $k => $v) {
				$ret[$v['order_id']] = $v;
			}
		}
		return $ret;
	}

	/**
	 * 插入订单
	 */
	public static function addOrder($orderData) {
		self::initDB();
		$orderId	= array();
		foreach($orderData as $key => $val) {
			$orderId[] = $val['order_id'];
		}
		$ret	= self::getOrderDataById($orderId);
		foreach($orderData as $k => $v) {		//删除重复的订单
			if(isset($ret[$v['order_id']])) {
				unset($orderData[$k]);
			}
		}
		$orderInfo = array();
		if(!isset($orderData[0]['data'])) {
			self::$errCode	= '1001';
			self::$errMsg	= '没有订单数据';
			return false;
		}
		$insertSql = array();
		foreach($orderData[0]['data'] as $k => $v) {
			$skuInfo		= explode('#', $v['Order']['sku']);
			$trueSku		= $skuInfo[0] === 'ZSON' ? $skuInfo[1] : $skuInfo[0];		//兼容处理
			$orderInfo[] = array(
				'order_id'							=> $v['Order']['order_id'],
				'ShippingDetail_city'				=> $v['Order']['ShippingDetail']['city'],
				'ShippingDetail_country'			=> $v['Order']['ShippingDetail']['country'],
				'ShippingDetail_name'				=> $v['Order']['ShippingDetail']['name'],
				'ShippingDetail_phone_number'		=> $v['Order']['ShippingDetail']['phone_number'],
				'ShippingDetail_state'				=> $v['Order']['ShippingDetail']['state'],
				'ShippingDetail_street_address1'	=> $v['Order']['ShippingDetail']['street_address1'],
				'ShippingDetail_zipcode'			=> $v['Order']['ShippingDetail']['zipcode'],
				'last_updated'						=> $v['Order']['last_updated'],
				'order_time'						=> strtotime($v['Order']['order_time']),
				'order_total'						=> $v['Order']['order_total'],
				'product_id'						=> $v['Order']['product_id'],
				'buyer_id'							=> $v['Order']['buyer_id'],
				'quantity'							=> $v['Order']['quantity'],
				'price'								=> $v['Order']['price'],
				'cost'								=> $v['Order']['cost'],
				'shipping'							=> $v['Order']['shipping'],
				'shipping_cost'						=> $v['Order']['shipping_cost'],
				'product_name'						=> $v['Order']['product_name'],
				'product_image_url'					=> $v['Order']['product_image_url'],
				'days_to_fulfill'					=> $v['Order']['days_to_fulfill'],
				'hours_to_fulfill'					=> $v['Order']['hours_to_fulfill'],
				'sku'								=> $v['Order']['sku'],
				'state'								=> $v['Order']['state'],
				'transaction_id'					=> $v['Order']['transaction_id'],
				'variant_id'						=> $v['Order']['variant_id'],
				'trueSku'							=> $trueSku,
			);
			$insertSql[] = '("'.implode('","', end($orderInfo)).'")';
		}

		$sql	= 'insert into ws_order (`'.implode('`,`', array_keys($orderInfo[0])).'`) values '.implode(',', $insertSql);
		$query	= self::$dbConn->query($sql);
		return $query;
	}
}