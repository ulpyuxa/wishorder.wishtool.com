<?php
include WEB_PATH.'lib/sdk/wishSdk/wishBase.class.php';
class WishOrderApi extends WishBase {
	/**
	 * 功能: 初使化
	 */
	public function __construct($account, $companyId) {
		parent::__construct($account, $companyId);
		$this->api = 'order';
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

	/**
	 *功能：上传跟踪号
	 */
	public function fulFillOrder($orderId, $trackingProvider, $trackingNumber, $shipNote="") {
		$this->act = 'fulfill-one';
		$para	= array(
			'id'				=> $orderId,
			'tracking_provider'	=> $trackingProvider,
			'tracking_number'	=> $trackingNumber
		);
		if(!empty($shipNote)) {
			$para['ship_note'] = $shipNote;
		}
		return $ret	= $this->sendHttpRequest($para);
	}

	/**
	 * 功能：修改订单的跟踪号
	 */
	public function modifyTracking($orderId, $trackingProvider, $trackingNumber, $shipNote="") {
		$this->act = 'modify-tracking';
		$para	= array(
			'id'				=> $orderId,
			'tracking_provider'	=> $trackingProvider,
			'tracking_number'	=> $trackingNumber
		);
		if(!empty($shipNote)) {
			$para['ship_note'] = $shipNote;
		}
		return $ret	= $this->sendHttpRequest($para);
	}
}