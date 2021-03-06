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
			'main_image'		=> isset($data['main_image']) ? $data['main_image'] : '',
			'name'				=> isset($data['name']) ? $data['name'] : '',
			'description'		=> isset($data['description']) ? $data['description'] : '',
			'tags'				=> isset($data['tags']) ? $data['tags'] : '',
			'sku'				=> isset($data['sku']) ? $data['sku'] : '',
			'inventory'			=> isset($data['inventory']) ? $data['inventory'] : '',
			'price'				=> isset($data['price']) ? $data['price'] : '',
			'shipping'			=> isset($data['shipping']) ? $data['shipping'] : '',
			'extra_images'		=> isset($data['extra_images']) ? $data['extra_images'] : '',
			'parent_sku'		=> isset($data['parent_sku']) ? $data['parent_sku'] : '',
			'msrp'				=> isset($data['msrp']) ? $data['msrp'] : '',
			'color'				=> isset($data['color']) ? $data['color'] : '',
			'size'				=> isset($data['size']) ? $data['size'] : '',
			'brand'				=> isset($data['brand']) ? $data['brand'] : '',
			'shipping_time'		=> isset($data['shipping_time']) ? $data['shipping_time'] : '',
			'landing_page_url'	=> isset($data['landing_page_url']) ? $data['landing_page_url'] : '',
			'upc'				=> isset($data['upc']) ? $data['upc'] : '',
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

	/**
	 * 功能: 修改子料号的信息，暂只支持修改价格
	 */
	public function variantUpdate($data) {
		$this->api	= 'variant';
		$this->act	= 'update';
		$para	= array(
			'sku'	=> urlencode($data['sku']),
			'price'	=> urlencode($data['price']),
		);
		return $ret = $this->sendHttpRequest($para);
	}
}