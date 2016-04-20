<?php
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
		'login_password' => 'pdcxaje127',
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
		'login_password' => 'pdcxaje127',
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
		'login_password' => 'pdcxaje127',
		//'token' => '12763,70bea707121e3c7f247f39eee265e63a',
		'format' => 'json',
		'lang' => 'cn',
		'error_on_empty' => 'no',
	);
	$post_data = post_data($url, $config);
	return json_decode($post_data, true);
}
function getIp() {
	$myip	= file_get_contents('http://ip.taobao.com/service/getIpInfo2.php?ip=myip');
	$ipInfo	= json_decode($myip, true);
	$ip		= $ipInfo['data']['ip'];
	return $ip;
}
$domainInfo = getDomainInfo();
$domianId	= $domainInfo['domains'][0]['id'];
$records = domainList($domainInfo['domains'][0]['id']);
$myIp	= getIp();
foreach($records['records'] as $k => $v) {
	if(in_array($v['name'],array('phpmyadmin','order', 'www')) && $v['value'] !== $myIp) {
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
	} else if(in_array($v['name'],array('phpmyadmin','order', 'www')) && $v['value'] === $myIp) {
		echo '子域名：'.$v['name'].'不需要进行修改！', PHP_EOL;
	}
}
