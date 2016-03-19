<?php
include WEB_PATH.'lib/sdk/wishSdk/wishBase.class.php';
class WishOrderApi extends WishBase {
	/**
	 * 功能: 初使化
	 */
	public function __construct($account, $companyId) {
		parent::__construct($account, $companyId);
		parent::$api = 'order';
		//parent::$url = 'https://merchant.wish.com/api/v1/order/multi-get?key=';
	}

	public function getAllorder($start = 0, $count = 50) {
		$para = array(
			'start'	=> $start,
			'count'	=> $count,
		);
		$ret = $this->sendHttpRequest($para);	//获取单个url
		return $ret;
	}

	public function fulFillOrder($orderId, $trackingProvider, $trackingNumber, $shipNote) {
		parent::$url	= 'https://china-merchant.wish.com/api/v2/order/fulfill-one?key=';
		$para	= array(
			'id'				=> $orderId,
			'tracking_provider'	=> $trackingProvider,
			'tracking_number'	=> $trackingNumber,
			'ship_note'			=> $shipNote
		);
		$ret	= $this->sendHttpRequest($para);
	}
}