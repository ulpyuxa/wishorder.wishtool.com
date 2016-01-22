<?php
/**
 * 功能: curl多线程类，可以并行获取多个url地址
 */
class ParallelCurl {
    public $max_requests;
    public $options;
    public $outstanding_requests;
    public $multi_handle;
	public $result;
    
    public function __construct($in_max_requests = 10, $in_options = array()) {
        $this->max_requests = $in_max_requests;
        $this->options = $in_options;
        
        $this->outstanding_requests = array();
        $this->multi_handle = curl_multi_init();
    }
    
    //Ensure all the requests finish nicely
    public function __destruct() {
    	$this->finishAllRequests();
    }
    // Sets how many requests can be outstanding at once before we block and wait for one to
    // finish before starting the next one
    public function setMaxRequests($in_max_requests) {
        $this->max_requests = $in_max_requests;
    }
    
    // Sets the options to pass to curl, using the format of curl_setopt_array()
    public function setOptions($in_options) {
        $this->options = $in_options;
    }
    // Start a fetch from the $url address, calling the $callback function passing the optional
    // $user_data value. The callback should accept 3 arguments, the url, curl handle and user
    // data, eg on_request_done($url, $ch, $user_data);
    public function startRequest($url, $callback, $user_data = array(), $post_fields=null) {
		if( $this->max_requests > 0 ) {
	        $this->waitForOutstandingRequestsToDropBelow($this->max_requests);
		}
    
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt_array($ch, $this->options);
        curl_setopt($ch, CURLOPT_URL, $url);
        if (isset($post_fields)) {
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
        }
        
        curl_multi_add_handle($this->multi_handle, $ch);
        
        $ch_array_key = (int)$ch;
        $this->outstanding_requests[$ch_array_key] = array(
            'url'		=> $url,
            'callback'	=> $callback,
            'user_data'	=> $user_data,
        );
        
		$this->checkForCompletedRequests();
    }
    
    // You *MUST* call this function at the end of your script. It waits for any running requests
    // to complete, and calls their callback functions
    public function finishAllRequests() {
        $this->waitForOutstandingRequestsToDropBelow(1);
    }
    // Checks to see if any of the outstanding requests have finished
    public function checkForCompletedRequests() {
		/*
        // Call select to see if anything is waiting for us
        if (curl_multi_select($this->multi_handle, 0.0) === -1)
            return;
        
        // Since something's waiting, give curl a chance to process it
        do {
            $mrc = curl_multi_exec($this->multi_handle, $active);
        } while ($mrc == CURLM_CALL_MULTI_PERFORM);
        */
        // fix for https://bugs.php.net/bug.php?id=63411
		do {
			$mrc = curl_multi_exec($this->multi_handle, $active);
		} while ($mrc == CURLM_CALL_MULTI_PERFORM);

		while ($active && $mrc == CURLM_OK) {
			if (curl_multi_select($this->multi_handle) != -1) {
				do {
					$mrc = curl_multi_exec($this->multi_handle, $active);
				} while ($mrc == CURLM_CALL_MULTI_PERFORM);
			} else {
				return;
			}
		}
        // Now grab the information about the completed requests
        while ($info = curl_multi_info_read($this->multi_handle)) {
        
            $ch				= $info['handle'];
            $ch_array_key	= (int)$ch;
            
            if (!isset($this->outstanding_requests[$ch_array_key])) {
                die("Error - handle wasn't found in requests: '$ch' in ".
                    print_r($this->outstanding_requests, true));
            }
            
            $request	= $this->outstanding_requests[$ch_array_key];
            $url		= $request['url'];
            
            $callback	= $request['callback'];
            $user_data	= $request['user_data'];
			//$this->result[] = $content;
            
            //call_user_func($callback, $content, $url, $ch, $user_data);
			for($i = 0; $i < 100; $i++) {
				$content	= curl_multi_getcontent($ch);
				$httpCode	= curl_getinfo($ch, CURLINFO_HTTP_CODE);
				$tmpRet		= array();
				if ($httpCode === 200) {
					$tmpRet = json_decode($content, true);
				}
				if(isset($tmpRet['data'])) {
					$this->result[current($user_data)] = $tmpRet;
					break;
				}
				usleep(15000);
			}

            unset($this->outstanding_requests[$ch_array_key]);
            curl_multi_remove_handle($this->multi_handle, $ch);
        }
    }
    
    // Blocks until there's less than the specified number of requests outstanding
    private function waitForOutstandingRequestsToDropBelow($max)
    {
        while (1) {
            $this->checkForCompletedRequests();
            if (count($this->outstanding_requests) < $max) {
            	break;
			}
            usleep(10000);
        }
    }
}

/**
//以下为测试代码，可根据自己的情况
$urls = array('1' =>  'http://ebay.oversold.valsun.cn/api.php?mod=apiOversold&act=getSkuStatus&jsonp=1&sku=CB032018_W&location=CN',
			'2' => 'http://ebay.oversold.valsun.cn/api.php?mod=apiOversold&act=getSkuStatus&jsonp=1&sku=OS000408&location=CN',
			'3' => 'http://ebay.oversold.valsun.cn/api.php?mod=apiOversold&act=getSkuStatus&jsonp=1&sku=OS003772_R&location=CN',
			'4' => 'http://ebay.oversold.valsun.cn/api.php?mod=apiOversold&act=getSkuStatus&jsonp=1&sku=SV018344_M&location=CN',
			'5' => 'http://ebay.oversold.valsun.cn/api.php?mod=apiOversold&act=getSkuStatus&jsonp=1&sku=SV023898_2&location=CN',
			'6' => 'http://ebay.oversold.valsun.cn/api.php?mod=apiOversold&act=getSkuStatus&jsonp=1&sku=SV014134_WR_XXL&location=CN',
			'7' => 'http://ebay.oversold.valsun.cn/api.php?mod=apiOversold&act=getSkuStatus&jsonp=1&sku=SV025641_DBL&location=CN',
			'8' => 'http://ebay.oversold.valsun.cn/api.php?mod=apiOversold&act=getSkuStatus&jsonp=1&sku=SV027047_L&location=CN',
			'9' => 'http://ebay.oversold.valsun.cn/api.php?mod=apiOversold&act=getSkuStatus&jsonp=1&sku=SV025088_L&location=CN',
			'10' => 'http://ebay.oversold.valsun.cn/api.php?mod=apiOversold&act=getSkuStatus&jsonp=1&sku=SV016528_P_L&location=CN'
		);

$max_requests = isset($argv[1]) ? $argv[1] : 10;
$curl_options = array(
    CURLOPT_SSL_VERIFYPEER => FALSE,
    CURLOPT_SSL_VERIFYHOST => FALSE,
    CURLOPT_USERAGENT, 'Parallel Curl test script',
);
$parallel_curl = new ParallelCurl($max_requests, $curl_options);
foreach ($urls as $terms) {
    $parallel_curl->startRequest($terms, 'on_request_done');
}
//var_dump($parallel_curl->result);
// This should be called when you need to wait for the requests to finish.
// This will automatically run on destruct of the ParallelCurl object, so the next line is optional.
$parallel_curl->finishAllRequests();
print_r($parallel_curl->result);
*/