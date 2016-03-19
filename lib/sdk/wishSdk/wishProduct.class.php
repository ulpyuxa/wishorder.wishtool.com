<?php
include WEB_PATH.'lib/sdk/wishSdk/wishBase.class.php';
class WishProductApi extends WishBase {
	/**
	 * 功能: 初使化
	 */
	public function __construct($account, $companyId) {
		parent::__construct($account, $companyId);
		$this->api = 'product';
		//parent::$url = 'https://merchant.wish.com/api/v1/product/'.$act.'?key=';
	}

	public function getAllProduct($start = 0, $count = 500) {
		$this->$act = 'multi-get';
		$para = array(
			'start'	=> $start,
			'count'	=> $count,
		);
		$ret = $this->sendHttpRequest($para);	//获取单个url
		return $ret;
	}

	/**
	 * 功能: 重新上架整个商品
	 */
	public function enabledProduct($productId, $spu) {
		$this->act = 'enable';
		$para = array(
			'id'			=> $productId,
			'parent_sku'	=> $spu,
		);
		$ret = $this->sendHttpRequest($para);
		return $ret;
	}

	/**
	 * 功能: 下架整个商品
	 */
	public function disableProduct($productId, $spu) {
		$this->act = 'disable';
		$para = array(
			'id'			=> $productId,
			'parent_sku'	=> $spu,
		);
		$ret = $this->sendHttpRequest($para);
		return $ret;
	}
}