<?php
class WishProductAct extends CommonAct{
	public function __construct() {
		parent::__construct();
		$this->smarty->template_dir = WEB_PATH.'html/templates/listing';	//设置本功能所有页面的模板路径
	}

	/**
	 * 获取线上所有的商品列表信息
	 */
	public function act_getWishProduct() {
		$listingData = WishProductModel::getWishProduct($start, $count);
	}

	/**
	 * 功能：显示产品列表信息到页面上
	 */
	public function act_wishProductList() {
		$productData	= WishProductModel::productList();
		$this->smarty->assign('data', $listingData[0]);
		$this->smarty->display('wishListinglist.tpl');
	}
}