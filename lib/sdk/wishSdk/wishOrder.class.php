<?php
include WEB_PATH.'lib/sdk/wishSdk/wishBase.class.php';
class WishOrderApi extends WishBase {
	/**
	 * 功能: 初使化
	 */
	public function __construct($account, $companyId) {
		parent::__construct($account, $companyId);
	}

	public function getAllorder($start = 0, $count = 50) {
		$para = array(
			'start'	=> $start,
			'count'	=> $count,
		);
		$ret = $this->sendHttpRequest($para);	//获取单个url
		return $ret;
	}
}