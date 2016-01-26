<?php
include WEB_PATH.'lib/sdk/wishSdk/wishBase.class.php';
class WishProductApi extends WishBase {
	/**
	 * 功能: 初使化
	 */
	public function __construct($account, $companyId) {
		parent::__construct($account, $companyId);
		parent::$url = 'https://merchant.wish.com/api/v1/product/multi-get?key=';
	}

	public function getAllProduct($start = 0, $count = 500) {
		$para = array(
			'start'	=> $start,
			'count'	=> $count,
		);
		$ret = $this->sendHttpRequest($para);	//获取单个url
		return $ret;
	}
}