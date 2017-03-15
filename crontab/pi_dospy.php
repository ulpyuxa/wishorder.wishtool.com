<?php
//$str = "{ip:'183.238.188.194',address:'广东省深圳市 移动'}";
//preg_match('/\b\d+\.\d+\.\d+\.\d+/', $str, $arr);
//PRINT_R($arr);EXIT;
function post_data($url, $data) {
	if ($url == '' || !is_array($data)) {
		return false;
	} 
	$ch = @curl_init();
	if (!$ch) {
		exit(json_encode(array('msg' => '内部错误：服务器不支持CURL')));
	} 
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
	curl_setopt($ch, CURLOPT_USERAGENT, 'DNSPod MYDDNS/0.1 (i@biner.me)');
	$result = curl_exec($ch);
	curl_close($ch);
	return $result;
} 
function domainList($domainId) {
	$url = 'https://dnsapi.cn/Record.List';
	$config = array(
		'login_email' => 'ulpyuxa@163.com',
		'login_password' => 'pdcxaje127,',
		'format' => 'json',
		'lang' => 'cn',
		'error_on_empty' => 'no',
		'domain_id'	=> $domainId,
		);
	$post_data = post_data($url, $config);
	return json_decode($post_data, true);
} 
function updateDomain($para) {
	$url = 'https://dnsapi.cn/Record.Modify';
	$config = array(
		'login_email' => 'ulpyuxa@163.com',
		'login_password' => 'pdcxaje127,',
		'format'			=> 'json',
		'lang'				=> 'cn',
		'error_on_empty'	=> 'no',
		'domain_id'			=> $para['domain_id'],
		'record_id'			=> $para['record_id'],
		'sub_domain'		=> $para['sub_domain'],
		'value'				=> $para['value'],
		'record_type'		=> $para['record_type'],
		'record_line'		=> $para['record_line'],
	);
	$post_data = post_data($url, $config);
	return json_decode($post_data, true);
}
function getDomainInfo() {
	$url = 'https://dnsapi.cn/Domain.List';
	$config = array(
		'login_email' => 'ulpyuxa@163.com',
		'login_password' => 'pdcxaje127,',
		//'token' => '12763,70bea707121e3c7f247f39eee265e63a',
		'format' => 'json',
		'lang' => 'cn',
		'error_on_empty' => 'no',
	);
	$post_data = post_data($url, $config);
	return json_decode($post_data, true);
}
function getOpenWRTIP(){
	$url = 'http://192.168.1.1/ip.html';
	$ip = file_get_contents($url);
	return trim($ip);
}
function getIp1() {		//用来获取路由器的外网IP
	//$url = 'http://ip.taobao.com/service/getIpInfo2.php?ip=myip';
	$url = 'http://ip.chinaz.com/getip.aspx';
	ini_set('user_agent', "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 2.0.50727; .NET CLR 3.0.04506.30; GreenBrowser)");
	$opts = array( 
			'http' => array (
				'method'	=> "GET",
				'timeout'	=> 3,
			)
		);
	$myIp		= @file_get_contents($url, false, stream_context_create($opts));
	$str = $myIp;
	preg_match('/\b\d+\.\d+\.\d+\.\d+/', $str, $arr);
	return $arr[0];
}
function getIp2() {		//用来获取路由器的外网IP
	$url = 'http://members.3322.org/dyndns/getip';
	ini_set('user_agent', "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 2.0.50727; .NET CLR 3.0.04506.30; GreenBrowser)");
	$opts = array( 
			'http' => array (
				'method'	=> "GET",
				'timeout'	=> 3,
			)
		);
	$myIp		= @file_get_contents($url, false, stream_context_create($opts));
	return trim($myIp);
}
//先得到IP
function getIp3() {
    $url = 'http://www.ip138.com/ip2city.asp';
	ini_set('user_agent', "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 2.0.50727; .NET CLR 3.0.04506.30; GreenBrowser)");
	$opts = array( 
			'http' => array (
				'method'	=> "GET",
				'timeout'	=> 3,
			)
		);
	$html		= @file_get_contents($url, false, stream_context_create($opts));
    preg_match('/\[(.*)\]/', $html, $myIp);
    return $myIp[1];
}
/*$myIp	= getIp1();
if(empty($myIp)) {
	$myIp	= getIp2();
}
if(empty($myIp)) {
	$myIp	= getIp3();
}*/
$myIp = getOpenWRTIP();
$saveIp = "";
if(is_file(__DIR__.'/saveIp.txt')) {
	$saveIp = file_get_contents(__DIR__.'/saveIp.txt');
}
if(empty($myIp)) {
        exit('本次未获取到IP，等待下次重试');
}
if($myIp === $saveIp) {
        exit("本次获取的IP{$myIp}与存储的IP{$saveIp}一致，不需要再进行解析\r\n");
}
file_put_contents(__DIR__.'/saveIp.txt', $myIp);        //将IP存储到文件中

echo '新IP：'.$myIp, PHP_EOL;

//得到IP后再进行域名解析

$domainInfo = getDomainInfo();
$domainId	= 0;
foreach($domainInfo['domains'] as $key => $val) {
	if($val['name'] === 'wiitool.com') {
		$domainId = $val['id'];
	}
}
if(empty($domainId)) {
	exit('本次未获取到domainId');
}
$records = domainList($domainId);

$siteArr= array('pi-order','order', 'mysql', 'www','invoice', 'laravel');
foreach($records['records'] as $k => $v) {
	if(in_array($v['name'],$siteArr) && $v['value'] !== $myIp) {
		$para = array(
			'domain_id'		=> $domainInfo['domains'][0]['id'],
			'record_id'		=> $v['id'],
			'sub_domain'	=> $v['name'],
			'value'			=> $myIp,
			'record_type'	=> $v['type'],
			'record_line'	=> $v['line'],
		);
		$status = updateDomain($para);
		if(intval($status['status']['code']) === 1) {
			echo $v['name']. '修改成功! 原因:ip不一致原始绑定IP:'.$v['value'].', 当前服务器IP:'.$myIp, PHP_EOL;
		}
	} else if(in_array($v['name'], $siteArr) && $v['value'] === $myIp) {
		echo '子域名：'.$v['name'].'不需要进行修改！', PHP_EOL;
	}
}
