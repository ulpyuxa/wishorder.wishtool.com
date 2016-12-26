<?php
/**
 * 功能 : wish api调用类
 * author : zxh
 * 日期 : 2016-12-09 14:11:58
 */
require_once 'vendor/autoload.php';
use Wish\WishClient;
class WishApi {
	var $enc = 'prod';	// prod, sandbox
	var $client;
	var $companyId = '1';
	/**
	 * 功能 : 构造函数，用于初使化账号的token等信息
	 * author : zxh
	 * 日期 : 2016-12-09 14:12:51
	 */
	public function __construct($account) {
		$accessToken = self::_getTokenInfo($account);
		if(empty($accessToken)) {
			return false;
		}
		$this->client = new WishClient($accessToken, $this->enc);
	}

	/**
	 * 功能 : 获取key文件的token信息
	 * author : zxh
	 * 日期 : 2016-12-09 14:33:27
	 */
	private function _getTokenInfo($account) {
		$tokenFile = WEB_PATH.'conf/key/'.$this->companyId.'/'.$account.'.key';
		if (is_file($tokenFile)) {
			$tokenStr = file_get_contents($tokenFile);
			$token	= json_decode($tokenStr, true);
			return $token['access_token'];
		}echo $tokenFile;exit(' +++++++++++++++ ');
		return false;
	}

	/**
	 * 功能 : 获取所有产品列表
	 * author : zxh
	 * 日期 : 2016-12-09 14:48:37
	 */
	public function getAllProducts() {
		$products = $this->client->getAllProducts();
		//$product_variations = $this->client->getAllProductVariations();
		return array('parent' => $products);//, 'variation' => $product_variations);
	}
}