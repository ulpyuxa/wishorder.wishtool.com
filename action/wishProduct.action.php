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
			$para	= array(
				'start'		=> 0,
				'query'		=> $_REQUEST['tags'],
				'transform'	=> true,
			);
			$data	= file_get_contents('https://www.wish.com/api/search?'.http_build_query($para));
			$data	= json_decode($data, true);
			$this->smarty->assign('data', $data['data']['results']);
		}
		$this->smarty->display('wishApiProductList.tpl');
	}
}