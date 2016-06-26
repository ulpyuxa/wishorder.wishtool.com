<?php
/**
 * 功能: 处理商品信息的model
 * max errCode: 1506
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
	public function getWishProduct($start = 0, $count = 50, $since="") {
		echo $start, ' ---- ', $count, PHP_EOL;
		$account		= 'geshan0728';
		$wishProduct	= new WishProductApi($account, 1);
		$products		= $wishProduct->getAllProduct($start, $count, $since);
		if(!empty($products[0]['data'])) {	//开始插入数据到数据库
			self::insertProductInfo($products[0]['data'], $account);
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
		return true;
	}

	/**
	 * 同步线上的listing并将listing数据写入数据库
	 */
	public function insertProductInfo($ret, $account) {
		self::initDB();
		$sql	= array();
		$ids	= array();
		$data	= array();
		$maxData= array();
		foreach($ret as $k => $v) {
			$skuInfo	= explode('#', $v['Product']['parent_sku']);
			$trueSpu	= $skuInfo[0] === 'ZSON' ? $skuInfo[1] : $skuInfo[0];		//兼容处理
			//判断上下架
			$isOnline	= 'offline';
			$count		= count($v['Product']['variants']);
			if($count === 1 && $v['Product']['variants'][0]['Variant']['enabled'] === 'True') {
				$isOnline = 'online';
			}
			if($count > 1) {
				$offlineCount	= 0;
				foreach($v['Product']['variants'] as $varKey => $varVal){
					if($varVal['Variant']['enabled'] === 'False') {
						$offlineCount++;
					}
				}
				if($count > $offlineCount) {
					$isOnline = 'online';
				}
			}
			$data[$v['Product']['id']] = array(
				'account'		=> $account,
				'productId'		=> $v['Product']['id'],
				'spu'			=> $trueSpu,
				'numSold'		=> $v['Product']['number_sold'],
				'saveSold'		=> $v['Product']['number_saves'],
				'isVariants'	=> count($v['Product']['variants']) > 1 ? 'Yes' : 'No',
				'reviewStatus'	=> $v['Product']['review_status'],
				'title'			=> $v['Product']['name'],
				'isOnline'		=> $isOnline,
				'isPromoted'	=> $v['Product']['is_promoted'],
			);
			foreach($v['Product']['variants'] as $variantKey => $variantVal) {
				$maxData[$v['Product']['id']]['variantsSku']	= array(
					'account'				=> $account,
					'productId'				=> $v['Product']['id'],
					'spu'					=> $trueSpu,
					'variantsSku'			=> $variantVal['Variant']['sku'],
					'variantsColor'			=> $variantVal['Variant']['color'],
					'variantsPrice'			=> $variantVal['Variant']['price'],
					'variantsEnable'		=> $variantVal['Variant']['enabled'],
					'variantsShipping'		=> $variantVal['Variant']['shipping'],
					'variantsAll_images'	=> $variantVal['Variant']['all_images'],
					'variantsInventory'		=> $variantVal['Variant']['inventory'],
					'variantsShipping_time'	=> $variantVal['Variant']['shipping_time'],
					'variantsMsrp'			=> $variantVal['Variant']['msrp'],
					'variantsSize'			=> $variantVal['Variant']['size'],

				);
				$maxSql[$v['Product']['id'].$variantVal['Variant']['sku']] = '("'.implode('","', end($maxData)).'")';
			}
			$ids[] = $v['Product']['id'];
			$sql[$v['Product']['id']] = '("'.implode('","', end($data)).'")';
		}
		if(empty(end($data))) {		//没有拉取到数据
			self::$errCode	= '1501';
			self::$errMsg	= '没有拉取到数据';
			return false;
		}
		$idsSql	= 'select productId,spu from ws_product where productId in("'.implode('","', $ids).'")';
		$query		= self::$dbConn->query($idsSql);
		$ret		= self::$dbConn->fetch_array_all($query);
		//更新主表
		$updateInfo	= array();
		foreach($ret as $k => $v) {		//过滤重复的listing
			if(isset($data[$v['productId']])) {
				$updateInfo[] = $data[$v['productId']];
				unset($data[$v['productId']], $sql[$v['productId']]);
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
		//更新分表
		$num		= substr(md5($trueSpu), 0, 1);
		$updateData	= array();
		print_r($maxData);exit;
		foreach($maxData as $maxKey => $maxVal) {
			$sql	= 'select * from ws_product_'.$num.' where product="'.$maxVal['productId'].'" and variantsSku="'.$maxVal['variantsSku'].'"';
			$query	= self::$dbConn->query($sql);
			$ret	= self::$dbConn->fetch_array_all($query);
			if(!empty($ret)) {
				$updateData[] = $maxVal;
				unset($maxSql[$maxVal['productId'].$maxSql['variantsSku']], $maxData[$maxKey]);
				continue;
			}
		}
		if(!empty($maxData)) {	//插入分表数据
			$sql = 'insert into ws_product_'.$num.'(`'.implode('`,`', array_keys(end($maxData))).'`) values'.implode(',', $maxSql);
			$query	= self::$dbConn->query($sql);
		}
		if(!empty($updateData)) {
			self::updateProductSub($updateData);
		}
		return $query;
	}

	/**
	 * 功能: 更新商品分表的数据
	 */
	public function updateProductSub($data, $num) {
		self::initDB();

		self::$dbConn->autocommit(FALSE);
		foreach($data as $key => $val) {
			$updateKey	= array_keys($val);
			$setData	= array();
			foreach($updateKey as $updateKey => $updateVal) {
				$setData[] = $updateVal.'="'.$val[$updateVal].'"';
			}
			$sql	= 'update ws_product_'.$num.' set '.implode(',', $setData).' where productId="'.$val['productId'].'" and variantsSku="'.$val['variantsSku'].'"';
			$query	= self::$dbConn->query($sql);
		}
		return self::$dbConn->commit();		
	}

	/**
	 * 更新数据库中主表的商品信息
	 */
	public function updateProductInfo($data) {
		self::initDB();
		self::$dbConn->autocommit(FALSE);
		foreach($data as $k => $v) {
			$sql = 'update ws_product set numSold="'.$v['numSold'].'",
						saveSold = "'.$v['saveSold'].'",
						reviewStatus = "'.$v['reviewStatus'].'",
						isPromoted = "'.$v['isPromoted'].'",
						isOnline	= "'.$v['isOnline'].'"
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

		$page = isset($_REQUEST['page']) ? ((int) $_REQUEST['page']) : 1;

		$where = array();
		if(isset($_REQUEST['spu']) && !empty($_REQUEST['spu'])) {
			$where[] = ' spu = "'.mysqli_real_escape_string(self::$dbConn->link,$_REQUEST['spu']).'"';
		}
		if(isset($_REQUEST['productId']) && !empty($_REQUEST['productId'])) {
			$where[] = ' productId = "'.mysqli_real_escape_string(self::$dbConn->link,$_REQUEST['productId']).'"';
		}
		$limit	= ' limit '.(($page - 1)*30).', 30';
		$order	= array();
		if(!isset($_REQUEST['order'])) {
			$order[] = ' numSold desc';
		}
		if(isset($_REQUEST['order'])) {
			$order[] = ' '.$_REQUEST['orderBy'].' '.$_REQUEST['order'];
		}
		if(!isset($_REQUEST['isOnline'])) {
			$where = count($where) > 0 ? ' where isOnline="online" and '.implode(' and ', $where) : ' where isOnline="online" ';
		} else {
			$where[] = ' isOnline = "'.mysqli_real_escape_string(self::$dbConn->link,$_REQUEST['isOnline']).'"';
			$where = count($where) > 0 ? ' where '.implode(' and ', $where) : '';
		}
		$order = count($order) > 0 ? ' order by '.implode(',', $order).',spu asc' : '';
		//统计数据库中的数量
		$sql		.= 'select count(*) as count from ws_product '.(strlen($where) > 0 ? $where : '').$order;
		$query		= self::$dbConn->query($sql);
		$countRet	= self::$dbConn->fetch_array_all($query);

		//查询数据库的数据
		$sql	= 'select * from ws_product '.(strlen($where) > 0 ? $where : '').$order.$limit;
		$query	= self::$dbConn->query($sql);
		$ret	= self::$dbConn->fetch_array_all($query);

		//数据分页
		$pagination = new Pagination($page, $countRet[0]['count'], 30);
		$pageHtml	= $pagination->parse();

		//其他数据组装
		$statisticInfo	= self::statisticProduct();
		$_REQUEST['order'] = $_REQUEST['order'] === 'desc' ? 'asc' : 'desc';
		return array('data' => $ret, 'pagination' => $pageHtml, 'order' => $_REQUEST['order'], 'statisticInfo' => $statisticInfo,);
	}

	/**
	 * 功能: 统计产品各种状态的数量
	 */
	public function statisticProduct() {
		self::initDB();

		$sql	= 'SELECT COUNT(reviewStatus) as counts, reviewStatus FROM ws_product WHERE isOnline="online" GROUP BY reviewStatus';
		$query	= self::$dbConn->query($sql);
		$ret	= self::$dbConn->fetch_array_all($query);
		$data	= array();
		foreach($ret as $k => $v) {
			$data['count'] += $v['reviewStatus'] != 'rejected' ? $v['counts'] : 0;
			if(isset($v['reviewStatus']) && $v['reviewStatus'] === 'approved') {
				$data['approved'] = $v['reviewStatus'] === 'approved' ? $v['counts'] : 0;
			}
			if(isset($v['reviewStatus']) && $v['reviewStatus'] === 'pending') {
				$data['pending'] = $v['reviewStatus'] === 'pending' ? $v['counts'] : 0;
			}
			if(isset($v['reviewStatus']) && $v['reviewStatus'] === 'rejected') {
				$data['rejected'] = $v['reviewStatus'] === 'rejected' ? $v['counts'] : 0;
			}
		}
		//统计订单数，收藏数
		$sql	= 'SELECT SUM(`numSold`) AS countSold, SUM(`saveSold`) AS countSave FROM ws_product';
		$query	= self::$dbConn->query($sql);
		$ret	= self::$dbConn->fetch_array_all($query);

		//统计上下架数量
		$sql		= 'SELECT COUNT(`isOnline`) AS isOfflineCount FROM ws_product where isOnline="offline"';
		$query		= self::$dbConn->query($sql);
		$offlineRet	= self::$dbConn->fetch_array_all($query);
		$data['onlineCount']	= $data['count'];
		$data['offlineCount']	= $offlineRet[0]['isOfflineCount'];

		$data['countSold']	= $ret[0]['countSold'];
		$data['countSave']	= $ret[0]['countSave'];
		//print_r($data);exit;
		return $data;
	}

	/**
	 * 功能：根据productId查询listing信息
	 */
	public function getInfoByProductId($productId) {
		self::initDB();

		$sql	= 'SELECT spu, productId FROM ws_product WHERE productId = "'.$productId.'"';
		$query	= self::$dbConn->query($sql);
		return self::$dbConn->fetch_array_all($query);
	}

	/**
	 * 功能：上下架一个商品
	 */
	public function operateProduct($action = '') {
		self::initDB();

		$productId		= $_REQUEST['productId'];
		if(empty($productId)) {
			self::$errCode	= '1502';
			self::$errMsg	= '产品ID不正确！';
			return false;			
		}
		$action			= isset($_REQUEST['action']) ? $_REQUEST['action'] : $action;
		$productInfo	= self::getInfoByProductId($productId);
		$wishProduct = new WishProductApi('geshan0728', 1);
		if(strtolower($action) === 'offline') {
			$operate = $wishProduct->disableProduct($productId, $productInfo[0]['spu']);
		} elseif(strtolower($action) === 'online') {
			$operate = $wishProduct->enabledProduct($productId, $productInfo[0]['spu']);
		} else {
			self::$errCode	= '1503';
			self::$errMsg	= '请选择需要的操作...';
			return false;			
		}
		if(isset($operate[0]['code']) && empty($operate[0]['code'])) {
			$sql	= 'UPDATE ws_product SET isOnline="'.strtolower($action).'" WHERE productId="'.$productId.'"';
			$query	= self::$dbConn->query($sql);
			if(!$query) {
				self::$errCode	= '1505';
				self::$errMsg	= '更新数据库失败，请重试！';
				return false;
			}
			return true;
		}
		self::$errCode	= '1504';
		self::$errMsg	= $operate['code'].':'.$operate['message'];
		return false;
	}

	/**
	 * 功能：根据url地址，拉取wish平台的关键字标题和描述 
	 */
	public function getItemDetail($url) {
		set_time_limit(0);
		/*ini_set('user_agent', "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 2.0.50727; .NET CLR 3.0.04506.30; GreenBrowser)");
		$opts = array( 
			'http' => array (
				'method'	=> "GET",
				'timeout'	=> 3,
			)
		);
		$data		= @file_get_contents($url, false, stream_context_create($opts));
		$request	= json_decode($data,true);
		if(empty($request)) {
			for($i = 0; $i < 3; $i++) {
				sleep(5);
				$data		= @file_get_contents($url, false, stream_context_create($opts));
				$request	= json_decode($data,true);
				if(!empty($request)) {
					break;
				}
			}
		}*/
		$request = file_get_contents($url);
		if(strlen($request) < 1) {
			self::$errCode	= '1506';
			self::$errMsg	= '拉取listing详情失败';
			return false;
		}
		$start	= stripos($request, "pageParams['mainContestObj']");
		$end	= stripos($request, ";", $start);
		$str1	= substr($request, $start, ($end - $start));
		$str2	= substr($str1, 31, strlen($str1));
		$data	= json_decode($str2, true);

		$tags			= array();
		$merchantTags	= array();
		foreach($data['tags'] as $key => $val) {
			$tags[] = $val['name'];
		}
		foreach($data['merchant_tags'] as $key => $val) {
			$merchantTags[] = $val['name'];
		}
		return array('tags' => $tags, 'merchant_tags' => $merchantTags, $data['description'], $data['name'], $data['extra_photo_urls']);
	}
}