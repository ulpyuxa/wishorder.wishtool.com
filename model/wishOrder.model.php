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

		$where = ' where 1 ';
		if (isset($_REQUEST['state']) && $_REQUEST['state'] !== 'ALL') {
			$where .= ' and state="'.$_REQUEST['state'].'"';
		}
		$sql		= 'SELECT * FROM `ws_order`'.$where;
		$query		= self::$dbConn->query($sql);
		$ret		= self::$dbConn->fetch_array_all($query);
		$orderStat	= C('ORDERSTAT');
		foreach($ret as $k => $v) {
			$ret[$k]['stateZH'] = $orderStat[$v['state']];
		}
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
		foreach($orderData[0]['data'] as $key => $val) {
			$orderId[] = $val['Order']['order_id'];
		}
		$oldOrder	= array();
		$ret		= self::getOrderDataById($orderId);
		foreach($orderData[0]['data'] as $k => $v) {		//删除重复的订单
			if(isset($ret[$v['Order']['order_id']])) {
				$oldOrder[$v['Order']['order_id']] = $v['Order']['state'];
				unset($orderData[0]['data'][$k]);
			}
		}
		self::updateOrderInfo($oldOrder);
		if(!isset($orderData[0]['data']) || empty($orderData[0]['data'])) {
			self::$errCode	= '1001';
			self::$errMsg	= '遗憾, 还没有新订单';
			return false;
		}
		$orderInfo = array();
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

	public static function orderCount() {
		self::initDB();

		$orderStat	= C('ORDERSTAT');
		$orderCount	= array();
		foreach($orderStat as $k => $v) {
			$sql	= 'select count(*) as counts from ws_order where state = "'.$k.'"';
			$query	= self::$dbConn->query($sql);
			$ret	= self::$dbConn->fetch_array_all($query);
			$orderCount[$k] = $ret[0]['counts'];
		}
		$orderCount['sum'] = array_sum($orderCount);
		return $orderCount;
	}

	/**
	 * 根据ID，更新订单的状态
	 * @para	$orderState
	 */
	public static function updateOrderInfo($orderState) {
		self::initDB();

		if(!empty($orderState)) {
			self::$dbConn->autocommit(FALSE);
			foreach($orderState as $k => $v) {
				$sql	= 'update ws_order set state="'.$v.'" where order_id="'.$k.'"';
				$query	= self::$dbConn->query($sql);
			}
			self::$dbConn->commit();
		}
		return true;
	}
}