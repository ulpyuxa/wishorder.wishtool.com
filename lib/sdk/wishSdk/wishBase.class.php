<?php
class WishBase {
	public $token	= '';
	public $url		= 'https://merchant.wish.com/api/v1/order/multi-get?key=';	//JHBia2RmMiQxMDAka2ZMZW14T0NNRVpvVGVtOWQyNnR0USRwT0tvc0Q4ejBaMC9YaHg5UjQ4NWsxTDdzb1E=&start=0&count=50

	/**
	 * 功能: 初使化
	 */
	public function __construct($account, $companyId) {
		$accountCfg		= include WEB_PATH.'conf/key/'.$companyId.'/'.$account.'.php';
		$this->token	= $accountCfg['token'];
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
		$url	= $this->url.$this->token.'&'.http_build_query($para);
		$ret	= $this->curlResult(array($url));
		return $ret;
	}

	/**
	 * 功能: 多个请求,采用多线程技术
	 */
	public function sendHttpRequestMulti ($para) {
		$url = array();
		foreach($para as $key => $val) {
			$url[] = $this->url.$this->token.'&'.http_build_query($para);
		}
		$ret = $this->curlResult($url);
		return $ret;
	}
}