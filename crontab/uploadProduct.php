<?php
/**
 * 功能: 定时同步订单数量及状态
author: zxh
 * 日期: 2016/1/23 23:04
 */
exec('ps aux|grep '.basename(__FILE__).'|grep -v grep',$ps);
if(count($ps)>1){
 	exit();
}
error_reporting(E_ALL);
define("SYSTEM_CRONTAB_USER", "true"); //跳过所有权限验证
set_time_limit(0);
include substr(str_replace(DIRECTORY_SEPARATOR, '/', __DIR__), 0, stripos(__DIR__, 'crontab')) . "framework.php";
Core :: getInstance();
global $dbConn;

$logPath	= WEB_PATH.'log/productInfo/';
$files = array();
$d = dir($logPath);
while (false !== ($entry = $d->read())) {
	if(in_array($entry, array('.','..'))) {
		continue;
	}
	if(is_file($logPath.$entry)) {
		$files[] = $entry;
	}
}
$d->close();

$num	= 0;
foreach($files as $fileKey => $fileVal) {
	$productInfo	= file_get_contents($logPath.$fileVal);
	if(empty($productInfo)) {
		continue;
	}
	if($num > 100) {	//每天上传100个
		break;
	}
	$data			= json_decode($productInfo, true);
	$data			= explode("\n", $data['data']);
	$spuData		= json_decode($data[0], true);
	unset($data[0], $spuData['key']);
	array_pop($data);
	$skuData		= array();
	$price			= 0;
	$shipping		= 0;
	foreach($data as $dataKey => $dataVal) {
		$skuInfo	= json_decode($dataVal, true);
		$sku		= explode('#', $skuInfo['sku']);
		if(isset($skuData[$sku[0]])) {
			continue;
		}
		unset($skuInfo['key']);
		$price					= priceEdit($skuInfo['price'], $skuInfo['shipping']);
		$skuInfo['price']		= $price['price'];
		$skuInfo['shipping']	= $price['shipping'];
		$skuInfo['sku']			= $sku[0].'#P28d';
		$parentSku				= explode("#", $skuInfo['parent_sku']);
		$skuInfo['parent_sku']	= $parentSku[0].'#P28d';
		$skuData[$sku[0]] = $skuInfo;
	}
	$spuData['upc']			= '';
	$spuSku					= explode("#", $spuData['sku']);
	$spuParentSku			= explode("#", $spuData['parent_sku']);
	$nameInfo				= explode("#", $spuData['name']);
	$spuData['sku']			= $spuSku[0].'#P28d';
	$spuPrice				= priceEdit($spuData['price'], $spuData['shipping']);
	$spuData['price']		= $spuPrice['price'];
	$spuData['shipping']	= $spuPrice['shipping'];
	$spuData['parent_sku']	= $spuParentSku[0].'#P28d';
	$spuData['name']		= $nameInfo[0].'#P28d';
	$spuStatus				= $wishProductApi->createProductSpu($spuData);
	echo $spuSn; var_dump($spuStatus);
	foreach($skuData as $skuKey => $skuVal) {
		$skuStatus = $wishProductApi->createProductSku($skuVal);
		var_dump($skuStatus);
	}
	echo $spuSn, '上传完成，现在开始休息!', PHP_EOL;
	$time	= rand(5, 20);
	sleep($time);
	$num++;
}

function priceEdit($price, $shipping) {
	$skuPrice	= round(($price + $shipping - 1), 2);
	return array('price' => $skuPrice, 'shipping' => 1);
}
echo '所有商品全部上传完成，本次上传产品数量为：'.$num;