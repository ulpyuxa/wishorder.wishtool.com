<?php
/**
 * 功能: 定时同步订单数量及状态
author: zxh
 * 日期: 2016/1/23 23:04
 */
error_reporting(E_ALL);
define("SYSTEM_CRONTAB_USER", "true"); //跳过所有权限验证
set_time_limit(0);
include substr(str_replace(DIRECTORY_SEPARATOR, '/', __DIR__), 0, stripos(__DIR__, 'crontab')) . "framework.php";
Core :: getInstance();
global $dbConn;

$wishProductApi	= new WishProductApi('geshan0728', 1);

$logPath	= WEB_PATH.'log/productInfo/'.date('Y/m-d').'/';
if(!is_dir($logPath)) {
	mkdir($logPath, 0777, true);
}

$dirDat	= file_get_contents('http://wishtool.valsun.cn/json.php?mod=apiWish&act=getlistingLog&jsonp=1&dir=1');
$dirDat	= json_decode($dirDat, true);
foreach($dirDat['data'] as $k => $v) {
	$spuSn			= substr($v, 0, stripos($v, '.'));
	$productInfo	= file_get_contents('http://wishtool.valsun.cn/json.php?mod=apiWish&act=getlistingLog&jsonp=1&spuSn='.$spuSn);
	file_put_contents($logPath.$spuSn.'.log', $productInfo, FILE_APPEND);	//将数据写入日志备用
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
		$skuInfo['sku'] = $sku[0].'#P28d';
		$parentSku		= explode("#", $skuInfo['parent_sku']);
		$skuInfo['parent_sku'] = $parentSku[0].'#P28d';
		$skuData[$sku[0]] = $skuInfo;
	}
	$spuData['upc']			= '';
	$spuSku					= explode("#", $spuData['sku']);
	$spuParentSku			= explode("#", $spuData['parent_sku']);
	$spuData['sku']			= $spuSku[0].'#P28d';
	$spuData['parent_sku']	= $spuParentSku[0].'#P28d';
	//print_r($spuData);
	$spuStatus = $wishProductApi->createProductSpu($spuData);
	var_dump($spuStatus);
	//if($spuStatus) {
		//print_r($skuData);exit;
		foreach($skuData as $skuKey => $skuVal) {
			//print_r($skuVal);
			$skuStatus = $wishProductApi->createProductSku($skuVal);
			var_dump($skuStatus);
		}
		//var_dump($skuStatus);
	//}
}

echo '全部listing上传完成！';