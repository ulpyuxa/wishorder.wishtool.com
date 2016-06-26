<?php
error_reporting(E_ALL);
header("Content-type: text/html; charset=utf-8");
date_default_timezone_set('Asia/Shanghai');

include "../framework.php";
Core::getInstance();

$code	= $_REQUEST['code'];
$appDat	= include WEB_PATH.'conf/key/1/geshan0728_wishtool.cn.php';
$url	= 'https://merchant.wish.com/api/v2/oauth/access_token';
$data	= array(
	'client_id'		=> $appDat['Client_Id'],
	'client_secret'	=> $appDat['Client_Secret'],
	'code'			=> $code,
	'grant_type'	=> 'authorization_code',
	'redirect_uri'	=> 'https://order.wishtool.cn/ssl.php',
);
$ret		= curl($url, http_build_query($data));
$dat		= json_decode($ret, true);
$keyFile	= WEB_PATH.'conf/key/1/geshan0728_wishtool.cn.key';
file_put_contents($keyFile, json_encode($dat['data']), FILE_APPEND);

/*
*方法功能：远程传输数据
*/
function curl($url,$urlPost)
{   
	$curl = curl_init();
	curl_setopt($curl,CURLOPT_URL,$url);//设置你要抓取的URL
	curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);//设置CURL参数，要求结果保存到字符串还是输出到屏幕上
	curl_setopt($curl,CURLOPT_POST,1);//设置为POST提交
	curl_setopt($curl,CURLOPT_POSTFIELDS,$urlPost);//提交的参数
	$data=curl_exec($curl);//运行CURL，请求网页
	curl_close($curl);
	if($data)
	{
		return $data;
	}
	return false;			
}