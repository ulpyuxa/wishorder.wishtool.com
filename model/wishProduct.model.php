<?php
/**
 * 功能: 处理商品信息的model
 * max errCode: 1501
 */
class WishProductModel {
	public static $dbConn;
	static $errCode	=	0;
	static $errMsg	=	"";

	public function __construct() {
	}
	
	public static function initDB() {
		global $dbConn;
		self::$dbConn = $dbConn;
	}

	/**
	 * 获取wish服务器的商品信息，递归方式拉取
	 */
	public function getWishProduct($start = 0, $count = 50) {
		$wishProduct	= new WishProductApi('geshan0728', 1);
		$products		= $wishProduct->getAllProduct($start, $count);
		if(!empty($products[0]['data'])) {	//开始插入数据到数据库
			self::insertProductInfo($products[0]['data']);
		} else {	//如果没有数据则退出递归方法
			return true;
		}
		if(isset($products[0]['paging']['next'])) {
			$pageInfo	= parse_url($products[0]['paging']['next']);
			$paraInfo	= explode('&', $pageInfo['query']);
			$para		= array();
			foreach($paraInfo as $k => $v) {
				if(stripos($v, '=')) {
					if(stripos($v, 'count=') !== false) {
						$para['count'] = substr($v, (stripos($v, '=') + 1), strlen($v));
					}
					if(stripos($v, 'start=') === 0) {
						$para['start'] = substr($v, (stripos($v, '=') + 1), strlen($v));
						break;
					}
				}
			}
			if(!isset($para['start']) || empty($para['start'])) {	//如果没有参数则退出递归方法
				return true;
			}
			self::getWishProduct($start+50, 50);
		}
	}

	/**
	 * 同步线上的listing并将listing数据写入数据库
	 */
	public function insertProductInfo($ret) {
		self::initDB();
		$sql	= array();
		$ids	= array();
		$data	= array();
		foreach($ret as $k => $v) {
			$skuInfo		= explode('#', $v['Product']['parent_sku']);
			$trueSpu		= $skuInfo[0] === 'ZSON' ? $skuInfo[1] : $skuInfo[0];		//兼容处理
			$data[$v['Product']['id']] = array(
				'productId'		=> $v['Product']['id'],
				'spu'			=> $trueSpu,
				'numSold'		=> $v['Product']['number_sold'],
				'saveSold'		=> $v['Product']['number_saves'],
				'isVariants'	=> count($v['Product']['variants']) > 1 ? 'Yes' : 'No',
				'reviewStatus'	=> $v['Product']['review_status'],
				'title'			=> $v['Product']['name'],
				'isOnline'		=> 'Yes',
			);
			$ids[] = $v['Product']['id'];
			$sql[] = '("'.implode('","', end($data)).'")';
		}
		if(empty(end($data))) {		//没有拉取到数据
			self::$errCode	= '1501';
			self::$errMsg	= '没有拉取到数据';
			return false;
		}
		$idsSql	= 'select productId from ws_product where productId in("'.implode('","', $ids).'")';
		$query		= self::$dbConn->query($idsSql);
		$ret		= self::$dbConn->fetch_array_all($query);
		$updateInfo	= array();
		foreach($ret as $k => $v) {		//过滤重复的listing
			if(isset($data[$v['productId']])) {
				$updateInfo[] = $data[$v['productId']];
				unset($data[$v['productId']]);
			}
		}
		if(!empty($updateInfo)) {	//更新listing
			self::updateProductInfo($updateInfo);
		}
		if(empty($data)) {		//没有数据需要写入数据库
			return true;
		}
		//插入Listing
		$sql	= 'insert ws_product (`'.implode('`,`', array_keys(end($data))).'`) values '.implode(',', $sql);
		$query	= self::$dbConn->query($sql);
		return $query;
	}

	/**
	 * 更新数据库中的商品信息
	 */
	public function updateProductInfo($data) {
		self::initDB();
		self::$dbConn->autocommit(FALSE);
		foreach($data as $k => $v) {
			$sql = 'update ws_product set numSold="'.$v['numSold'].'",
						saveSold = "'.$v['saveSold'].'",
						reviewStatus = "'.$v['reviewStatus'].'"
						where productId = "'.$v['productId'].'"';
			$query = self::$dbConn->query($sql);
		}
		return self::$dbConn->commit();
	}

	/**
	 * 功能：获取数据库中的商品信息
	 */
	public function productList() {
		self::initDB();

		$sql = !isset($_REQUEST['order']) ? 'select * from ws_product order by numSold desc' : 'select * from ws_product ';
		if(isset($_REQUEST['order'])) {
			$sql .= ' order by '.$_REQUEST['orderBy'].' '.$_REQUEST['order'];
		}
		$_REQUEST['order'] = $_REQUEST['order'] === 'desc' ? 'asc' : 'desc';
		unset($_REQUEST['order'], $_REQUEST['orderBy']);
		$query	= self::$dbConn->query($sql);
		$ret	= self::$dbConn->fetch_array_all($query);
		return array('data' => $ret, 'order' => $order);
	}

	/**
	 * 功能: 统计产品各种状态的数量
	 */
	
}