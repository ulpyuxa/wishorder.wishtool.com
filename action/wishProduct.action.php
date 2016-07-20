<?php
/**
 * errCode:1601
 */
class WishProductAct extends CommonAct{
	static $errCode		= 0;
	static $errMsg		= "";
	static $imgVersion	= 0;

	public function __construct() {
		parent::__construct();
		$this->smarty->template_dir = WEB_PATH.'html/templates/listing';	//设置本功能所有页面的模板路径
	}

	/**
	 * 获取线上所有的商品列表信息
	 */
	public function act_getWishProduct() {
		set_time_limit(0);
		$listingData = WishProductModel::getWishProduct($start, $count);
		return $listingData;
	}

	/**
	 * 功能：显示产品列表信息到页面上
	 */
	public function act_wishProductList() {
		$productData = WishProductModel::productList();
		$this->smarty->assign('productData', $productData);
		$this->smarty->display('wishProductlist.tpl');
	}

	/**
	 * 功能：上架或下架一个商品
	 */
	public function act_operateProduct() {
		$operate = WishProductModel::operateProduct();
		return $operate;
	}

	/**
	 * 功能：抓虫获取wish平台上其他商家的tags字段
	 */
	public function act_getWishTags() {
		if(isset($_REQUEST['productUrl'])) {
			$data = WishProductModel::getItemDetail($_REQUEST['productUrl']);
			if(!$data) {
				self::$errCode	= WishProductModel::$errCode;
				self::$errMsg	= WishProductModel::$errMsg;
				return false;
			}
			//========开始翻译===========
			/*foreach($data['merchant_tags'] as $k => $v) {
				$merchantTagsNew[$k] = TranslateModel::translator($k);
			}
			foreach($data['tags'] as $k => $v) {
				$tags[$k] = TranslateModel::translator($k);
			}
			$data['tags'] = $tags;
			$data['merchant_tags'] = $merchantTagsNew;*/
			//========结束翻译===========
			return $data;
		}
		$this->smarty->display('wishOtherProduct.tpl');
	}

	/**
	 * 功能：按条件拉取wish的列表数据
	 * 接口名：https://www.wish.com/api/search?start=0&query=clothing&transform=true
	 */
	public function act_apiProductList() {
		set_time_limit(0);
		if(isset($_REQUEST['tags'])) {
			$url	= 'https://www.wish.com/api/search?transform=true&start=0&query='.rawurlencode($_REQUEST['tags']);
			$data	= $this->httpNew($url);
			$data	= json_decode($data, true);
			$this->smarty->assign('data', $data['data']['results']);
		}
		$this->smarty->display('wishApiProductList.tpl');
	}

	public function httpNew($url, $data='', $method='GET'){   
		$curl = curl_init(); // 启动一个CURL会话  
		curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址  
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 对认证证书来源的检查  
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, true); // 从证书中检查SSL加密算法是否存在  
		curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器  
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转  
		curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer  
		if($method=='POST'){  
			curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求  
			if ($data != ''){  
				curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包  
			}  
		}  
		curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环  
		curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容  
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回  
		$tmpInfo = curl_exec($curl); // 执行操作  
		curl_close($curl); // 关闭CURL会话  
		return $tmpInfo; // 返回数据  
	}

	/**
	 * 功能: 待刊登列表
	 */
	public function act_uploadProductList() {
		$path	= WEB_PATH.'log/productInfo';
		$data	= WishProductModel::getWaitSpu();
		$this->smarty->assign('productData', $data);
		$this->smarty->display('uploadProductList.tpl');
	}
	
	/**
	 * 功能: 查看并编辑待刊登料号
	 */
	public function act_editUploadProduct() {
		$spu		= $_REQUEST['spu'];
		$url		= 'http://api.fenxiao.valsun.cn/api.php?action=getDistributorOpenProducts&v=1.0&spu='.$spuSn.'&companyId=1553&platform=wish&warehouse=CN';
		$pushInfo	= file_get_contents($url);
		$pushInfo	= json_decode($pushInfo, true);
		if(!isset($pushInfo['data']) || empty($pushInfo['data'])) {		//此料号为未开放的料号不能刊登。
			WishProductModel::delWaitProduct($spu);		//删除可刊登料号
			header('location:'.getenv("HTTP_REFERER"));
		}
		$file	= WEB_PATH.'log/productInfo/'.$spu.'.log';
		if(!is_file($file)) {
			self::$errCode	= 1601;
			self::$errMsg	= '未找到'.$spu.'的上传资料！';
			return false;
		}
		$data	= self::readProductInfo($file, $spu);
		$images	= self::act_getImages($spu, self::$imgVersion);		//获取料号的所有图片地址
		$this->smarty->assign('data', $data);
		$this->smarty->assign('imgVersion', self::$imgVersion);
		$this->smarty->assign('spu', $spu);
		$this->smarty->assign('images', $images);
		$this->smarty->display('editUploadProduct.tpl');
	}

	/**
	 * 功能: 从log文件中提取上传资料
	 */
	private function readProductInfo($file, $spu) {
		$data		= file_get_contents($file);
		$dataArr	= explode('{"errCode', $data);
		$hasTags	= false;
		$ret		= array();
		$account	= isset($_COOKIE['account']) ? $_COOKIE['account'] : 'geshan0728';
		$accountAbbr= C('ACCOUNTABBR')[$account];
		$price		= WishProductModel::spuPrice($spu);		//获取价格已经减去$1的运费
		foreach($dataArr as $k => $v) {
			if(strlen($v) < 100) {
				continue;
			}
			$json		= json_decode('{"errCode'.$v, true);
			$ret		= explode("\n", $json['data']);
			foreach($ret as $retKey	=> $retVal) {
				if(empty($retVal)) {		//删除无用数据
					unset($ret[$retKey]);
					continue;
				}
				$ret[$retKey] = json_decode($retVal, true);
				unset($ret[$retKey]['key']);
				if(isset($ret[$retKey]['main_image'])) {
					$ret[$retKey]['main_image'] = self::imageReplace($ret[$retKey]['main_image'], 'img.pics.valsun.cn');
				}
				if(isset($ret[$retKey]['extra_images'])) {
					$ret[$retKey]['extra_images'] = self::imageReplace($ret[$retKey]['extra_images'], 'img.pics.valsun.cn');
				}
				if(stripos($ret[$retKey]['name'], '#') > 0) {		//对标题进行重新组装
					$titleInfo =  explode('#', $ret[$retKey]['name']);
					array_pop($titleInfo);
					$ret[$retKey]['name'] = implode('#', $titleInfo);
				}
				$ret[$retKey]['name'] = trim($ret[$retKey]['name']).' '.$accountAbbr;
				if(isset($ret[$retKey]['sku'])) {
					$skuInfo	= explode('#', $ret[$retKey]['sku']);
					$spuInfo	= explode('#', $ret[$retKey]['parent_sku']);
					$ret[$retKey]['sku']		= $skuInfo[0].'#'.$accountAbbr;
					$ret[$retKey]['parent_sku'] = $spuInfo[0].'#'.$accountAbbr;
					$ret[$retKey]['price']		= $price;
					$ret[$retKey]['shipping']	= 1;
				}
			}
			if(!empty($ret)) {
				break;
			}
		}
		$ret = array_filter($ret);
		return $ret;
	}

	/**
	 * 功能：提取图片数据
	 */
	public function imageReplace($images, $url='') {
		$images	= explode('|', $images);
		$url	= empty($url) ? 'images.wishtool.cn' : $url;
		foreach($images as $imagesKey => $imagesVal) {
			preg_match('/\/v\d+/i', $imagesVal, $arr);	//获取版本号，以第一个位置的url为准;
			$imgVer		= intval(substr($arr[0], 2, strlen($arr[0])));	//url路径中的版本号
			$imgInfo	= explode('.', $imagesVal);
			$imgName	= explode('-', basename($imagesVal));
			array_pop($imgName);
			if(strlen($imgVer) > 0) {
				self::$imgVersion	= $imgVer;
				$images[$imagesKey]	= 'http://'.$url.'/v'.$imgVer.'/'.implode('-', $imgName).'-zxhTest.'.end($imgInfo);
			} else {
				self::$imgVersion	= 1;
				$images[$imagesKey]	= 'http://'.$url.'/'.implode('-', $imgName).'-zxhTest.'.end($imgInfo);
			}
		}
		return $images;
	}
	/**
	 * 功能: 保存并刊登待刊登料号
	 */
	public function act_saveWaitProduct() {
		set_time_limit(0);
		return WishProductModel::saveWaitProduct();
	}

	/**
	 * 功能获取图片地址
	 */
	public function act_getImages($spuSn, $version) {
		$url	= "http://pics.valsun.cn/json.php?mod=apiPicture&act=getWishPicByAccountSpu1&jsonp=1&type=2&spuSn=".$spuSn."&account=360beauty&version=".$version."&companyId=1553";
		try{
			$ret	= file_get_contents($url);
		} catch(Exception $e) {
			$ret	= file_get_contents($url);
		}
		$ret	= json_decode($ret, true);
		foreach($ret['data']['360beauty'][$version] as $k => $v) {
			$url	= self::imageReplace($v, 'img.pics.valsun.cn');
			$ret['data']['360beauty'][$version][$k] = end($url);
		}
		return $ret['data']['360beauty'][$version];
	}

	/**
	 * 功能：从数据库中删除待上传料号的列表
	 */
	public function act_delWaitProduct(){
		return WishProductModel::delWaitProduct();
	}
}