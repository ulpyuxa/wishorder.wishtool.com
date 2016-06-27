<?php
class WishProductAct extends CommonAct{
	static $errCode	=	0;
	static $errMsg	=	"";

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
}