<?php
include WEB_PATH.'lib/sdk/wishSdk/wishBase.class.php';
class WishProductApi extends WishBase {
	/**
	 * 功能: 初使化
	 */
	public function __construct($account, $companyId) {
		parent::__construct($account, $companyId);
		$this->api	= 'product';

		//parent::$url = 'https://merchant.wish.com/api/v1/product/'.$act.'?key=';
	}

	public function getAllProduct($start = 0, $count = 500, $since = '') {
		$this->act = 'multi-get';
		$para = array(
			'start'			=> $start,
			'count'			=> $count,
		);
		if(!empty($since)) {
			$para['since'] = $since;
		}
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
	
	/**
	 * 功能: 创建商品主料号
	 */
	public function createProductSpu($data) {
		$this->api = 'product';
		$this->act = 'add';
		$para	= array(
			'main_image'		=> $data['main_image'],
			'name'				=> $data['name'],
			'description'		=> $data['description'],
			'tags'				=> $data['tags'],
			'sku'				=> $data['sku'],
			'inventory'			=> $data['inventory'],
			'price'				=> $data['price'],
			'shipping'			=> $data['shipping'],
			'extra_images'		=> $data['extra_images'],
			'parent_sku'		=> $data['parent_sku'],
			'msrp'				=> $data['msrp'],
			'color'				=> $data['color'],
			'size'				=> $data['size'],
			'brand'				=> $data['brand'],
			'shipping_time'		=> $data['shipping_time'],
			'landing_page_url'	=> $data['landing_page_url'],
			'upc'				=> $data['upc'],
		);
		return $ret = $this->sendHttpRequest($para);
	}

	/**
	 * 功能：创建商品子料号
	 */
	public function createProductSku($data) {
		$this->api = 'variant';
		$this->act = 'add';
		$para	= array(
			'sku'			=> $data['sku'],
			'inventory'		=> $data['inventory'],
			'price'			=> $data['price'],
			'size'			=> $data['size'],
			'shipping'		=> $data['shipping'],
			'parent_sku'	=> $data['parent_sku'],
			'color'			=> $data['color'],
			'msrp'			=> $data['msrp'],
			'main_image'	=> $data['main_image'],
			'shipping_time'	=> $data['shipping_time'],
		);
		return $ret = $this->sendHttpRequest($para);
	}
}