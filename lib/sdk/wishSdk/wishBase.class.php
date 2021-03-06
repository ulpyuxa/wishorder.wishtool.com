<?php
class WishBase {
	public static $token	= '';
	public static $url		= 'https://china-merchant.wish.com/api/v2/';	//JHBia2RmMiQxMDAka2ZMZW14T0NNRVpvVGVtOWQyNnR0USRwT0tvc0Q4ejBaMC9YaHg5UjQ4NWsxTDdzb1E=&start=0&count=50
	public static $access_token	= '';
	var $act = 'multi-get';
	var $api = 'order';

	/**
	 * 功能: 初使化
	 */
	public function __construct($account, $companyId) {
		$tokenInfo			= file_get_contents(WEB_PATH.'conf/key/1/'.$account.'.key');
		$tokenInfo			= json_decode($tokenInfo, true);
		self::$access_token	= $tokenInfo['access_token'];
	}

	public function setSandbox() {
		self::$url	= 'https://sandbox.merchant.wish.com/api/v2';
	}

	/**
	 * curl 多线程方法
	 */
	public function curlResult($urls) {
		include_once WEB_PATH.'lib/parallelCurl.class.php';
		$max_requests = isset($argv[1]) ? $argv[1] : 20;	//最大调用次数
		$curl_options = array(
			CURLOPT_SSL_VERIFYPEER => FALSE,
			CURLOPT_SSL_VERIFYHOST => FALSE,
			CURLOPT_USERAGENT, 'Parallel Curl test script',
		);
		$parallelCurl = new ParallelCurl($max_requests, $curl_options);
		foreach ($urls as $key => $terms) {
			if(empty($terms)) {
				continue;
			}
			$parallelCurl->startRequest($terms, '', array($key));
		}
		$parallelCurl->finishAllRequests();
		return $parallelCurl->result;
	}

	/**
	 * 功能: 单个请求
	 */
	public function sendHttpRequest ($para) {
		$paraStr = array();
		foreach ($para as $key => $val) {
			if($val === "") {
				unset($para[$key]);
				continue;
			}
			$paraStr[] = $key.'=%s';
		}
		$url	= self::$url.$this->api.'/'.$this->act.'?access_token='.self::$access_token.'&'.http_build_query($para);
		if($this->act === 'update' && $this->api === 'variant') {
			$url = sprintf(
				"https://china-merchant.wish.com/api/v2/".$this->act."/update?access_token=%s&sku=%s&&price=%s",
				self::$access_token, $para['sku'], $para['price']);
			$context = stream_context_create(array(
				'http' => array(
					'method'        => 'GET',
					'ignore_errors' => true,
				),
			));
			// Send the request
			$ret = file_get_contents($url, TRUE, $context);
		} else {
			$paraStr[] = 'access_token=%s';
			$para['access_token'] = self::$access_token;
			$url = vsprintf("https://china-merchant.wish.com/api/v2/".$this->api."/".$this->act."?".implode("&", $paraStr),$para);
			$context = stream_context_create(array(
				'http' => array(
					'method'        => 'GET',
					'ignore_errors' => true,
				),
			));
			// Send the request
			$ret = file_get_contents($url, TRUE, $context);
		}
		return json_decode($ret, true);
	}

	/**
	 * 功能: 多个请求,采用多线程技术
	 */
	public function sendHttpRequestMulti ($para) {
		$url = array();
		foreach($para as $key => $val) {
			$url[] = self::$url.self::$token.'&'.http_build_query($para);
		}
		$ret = $this->curlResult($url);
		return $ret;
	}
}