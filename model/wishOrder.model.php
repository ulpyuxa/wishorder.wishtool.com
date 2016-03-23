<?php
/**
 * 功能：订单相关操作
 * errorCode: 1105
 */
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
		
		//设置当前页及limit
		$page	= isset($_GET['page']) ? ((int) $_GET['page']) : 1;
		$limit	= ' limit '.(($page - 1)*30).', 30';

		$where = array();
		if (isset($_REQUEST['state']) && $_REQUEST['state'] !== 'ALL') {
			$where[] = ' state="'.$_REQUEST['state'].'"';
		}
		if (isset($_REQUEST['sku']) && !empty(trim($_REQUEST['sku']))) {
			$where[] = ' trueSku="'.trim($_REQUEST['sku']).'"';
		}
		if (isset($_REQUEST['spu']) && !empty(trim($_REQUEST['spu']))) {
			$where[] = ' trueSpu="'.trim($_REQUEST['spu']).'"';
		}
		$where = count($where) > 0 ? ' where '.implode(' and ', $where) : '';
		$order = ' order by order_time desc, sku ASC';
		//读取表的记录数量
		$sql		= 'SELECT count(*) as counts FROM `ws_order`'.$where;
		$query		= self::$dbConn->query($sql);
		$countRet	= self::$dbConn->fetch_array_all($query);

		//初使化分页类
		$pagination = new Pagination($page, $countRet[0]['counts'], 30);
		$pageHtml	= $pagination->parse();

		//分页读取记录
		$sql		= 'SELECT * FROM `ws_order`'.$where.$order.$limit;
		$query		= self::$dbConn->query($sql);
		$ret		= self::$dbConn->fetch_array_all($query);
		$orderStat	= C('ORDERSTAT');
		foreach($ret as $k => $v) {
			$ret[$k]['stateZH'] = $orderStat[$v['state']];
		}
		return array('data' => $ret, 'pageHtml' => $pageHtml);
	}

	/** 
	 * 根据订单ID查询订单
	 */
	public static function getOrderDataById($ids = array()) {
		self::initDB();
		$sql	= 'select * from ws_order where order_id in ("'.implode('","', $ids).'")';
		$query	= self::$dbConn->query($sql);
		$ret	= self::$dbConn->fetch_array_all($query);
		$data	= array();
		if(!empty($ret)) {
			foreach($ret as $k => $v) {
				$data[$v['order_id']] = $v;
			}
		}
		return $data;
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
			$trueSkuArr		= explode("_", $trueSku);
			$trueSpu		= $trueSkuArr[0];
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
				'trueSpu'							=> $trueSpu,
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

	/**
	 * 功能: 获取平台所有的运输方式，
	 */
	public function getPostList() {
		self::initDB();

		$sql	= 'SELECT * FROM `ws_post_method` ORDER BY isCommon ASC,post_en ASC';
		$query	= self::$dbConn->query($sql);
		$ret	= self::$dbConn->fetch_array_all($query);
		return $ret;
	}

	public function checkTrackingInfo($data) {
		$errStr = '';
		if(empty($data['orderId'])) {
			$errStr = '订单号输入错误！';
		}
		if(empty($data['transport'])) {
			$errStr = '运输方式输入错误！';
		}
		if(empty($data['trackNumber'])) {
			$errStr = '跟踪号输入错误！';
		}
		if(!empty($errStr)) {
			self::$errCode	= '1102';
			self::$errMsg	= $errStr;
			return false;
		}		
	}
	/**
	 * 功能: 上传跟踪号
	 */
	public function fulfillOrder() {
		self::initDB();
		$orderApi		= new WishOrderApi('geshan0728', 1);
		$errStr			= self::checkTrackingInfo($_REQUEST);
		if(strlen($errStr) > 0) {
			return false;
		}
		$uploadStatus	= $orderApi->fulFillOrder($_REQUEST['orderId'], $_REQUEST['transport'], $_REQUEST['trackNumber'], $_REQUEST['shipNote']);
		if(isset($uploadStatus[0]['code']) && empty($uploadStatus[0]['code'])) {
			//跟踪号上传成功
			$sql	= 'update ws_order set state="SHIPPED",shippingMethod="'.$_REQUEST['transport'].'",
											tracknumber="'.$_REQUEST['trackNumber'].'",
											shipNote="'.$_REQUEST['shipNote'].'" 
						where order_id="'.$_REQUEST['orderId'].'"';
			echo $sql;
			$query	= self::$dbConn->query($sql);
			if(!$query) {
				self::$errCode	= '1105';
				self::$errMsg	= '数据库添加运单号失败！请重试';
				return false;
			}
			return true;
		}
		self::$errCode	= '1103';
		self::$errMsg	= '未知错误，新增订单跟踪号上传失败';
		return false;
	}
	/**
	 * 功能: 修改跟踪号
	 */
	public function modifyTracking() {
		$orderApi		= new WishOrderApi('geshan0728', 1);
		$errStr			= self::checkTrackingInfo($_REQUEST);
		if(strlen($errStr) > 0) {
			return false;
		}
		$uploadStatus	= $orderApi->modifyTracking($_REQUEST['orderId'], $_REQUEST['transport'], $_REQUEST['trackNumber'], $_REQUEST['shipNote']);
		if(isset($uploadStatus[0]['code']) && empty($uploadStatus[0]['code'])) {
			//跟踪号上传成功
			return $uploadStatus;
		}
		self::$errCode	= '1104';
		self::$errMsg	= '未知错误，修改订单跟踪号上传失败';
		return false;
	}
}