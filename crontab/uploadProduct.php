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
$date = date('Y_m_d');

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

$newDir = WEB_PATH.'log/productInfo/'.date('Y/m-d').'/';
if(!is_dir($newDir)) {
	mkdir($newDir, 0777, true);
	if(!is_dir($newDir)) {
		exit('不能建立目录!');
	}
}
$errorDir = WEB_PATH.'log/productInfo/'.date('Y/m-d').'/errorProduct/';
if(!is_dir($newDir)) {
	mkdir($newDir, 0777, true);
	if(!is_dir($newDir)) {
		exit('不能建立目录!');
	}
}
$wishProductApi	= new WishProductApi('geshan0728', 1);
$num	= 0;
$uploadNum	= rand(15, 30);
foreach($files as $fileKey => $fileVal) {
	$spuInfo		= explode('.', $fileVal);
	$spuSn			= $spuInfo[0];
	errorLog('开始上传,'.$spuSn, 'tip');
	$sql			= 'select spu from `ws_product` where spu = "'.$spuSn.'"';
	$query			= $dbConn->query($sql);
	$ret			= $dbConn->fetch_array_all($query);
	if(!empty($ret)) {	//已经上传过此商品
		echo $spuSn, ', 此料号已经上传过了，将跳过上传！', PHP_EOL;
		rename($logPath.$fileVal, $errorDir.$spuSn.'.log');
		continue;
	}
	$price = spuPrice($spuSn);
	if($price <= 0.1) {		//如果料号的价格小于1，则跳过数据
		rename($logPath.$fileVal, $errorDir.$spuSn.'.log');
		continue;
	}
	$productInfo	= file_get_contents($logPath.$fileVal);
	if(empty($productInfo)) {
		rename($logPath.$fileVal, $errorDir.$spuSn.'.log');
		continue;
	}
	if($num > 15) {	//每天上传100个
		break;
	}
	$data			= json_decode($productInfo, true);
	$data			= explode("\n", $data['data']);
	$spuData		= json_decode($data[0], true);
	$tags			= explode(',' $spuData['tags']);
	if(count($tags) < $uploadNum) {		//如果tags数量小于5个，则不上传
		continue;
	}
	unset($data[0], $spuData['key']);
	array_pop($data);
	$skuData	= array();
	$skuInfo	= array();
	if(!empty($data)) {		//单料号没有子料号信息，所以不用进来
		foreach($data as $dataKey => $dataVal) {
			$skuInfo	= json_decode($dataVal, true);
			$sku		= explode('#', $skuInfo['sku']);
			if(isset($skuData[$sku[0]])) {
				continue;
			}
			unset($skuInfo['key']);
			$skuInfo['price']		= $price;
			$skuInfo['shipping']	= 1;	//默认运费是$1
			$skuInfo['sku']			= $sku[0].'#P28d';
			$parentSku				= explode("#", $skuInfo['parent_sku']);
			$skuInfo['parent_sku']	= $parentSku[0].'#P28d';
			$skuData[$sku[0]] = $skuInfo;
		}
	}
	$spuData['upc']			= '';
	$spuSku					= explode("#", $spuData['sku']);
	$spuParentSku			= explode("#", $spuData['parent_sku']);
	$nameInfo				= explode("#", $spuData['name']);
	$spuData['sku']			= $spuSku[0].'#P28d';
	$spuData['price']		= $price;
	$spuData['shipping']	= 1;	//默认运费是$1
	$spuData['parent_sku']	= $spuParentSku[0].'#P28d';
	$spuData['name']		= $nameInfo[0].'#P28d';
	$spuStatus				= $wishProductApi->createProductSpu($spuData);
	echo $spuSn;
	print_r($spuData);
	if(!empty($skuData)) {		//单料号没有子料号，所以不用进来
		foreach($skuData as $skuKey => $skuVal) {
			print_r($skuVal);
			$skuStatus = $wishProductApi->createProductSku($skuVal);
			var_dump($skuStatus);
		}
	}
	$time	= rand(10, 30);
	$msg	= $spuSn.'上传完成，现在开始休息!,时长：'.$time;
	echo $msg, PHP_EOL;
	errorLog($msg, 'tip');
	try {
		rename($logPath.$fileVal, $newDir.$spuSn.'.log');
	} catch (Exception $e) {
		echo '建立目录失败!';
	}
	sleep($time);
	$num++;
}

echo '所有商品全部上传完成，本次上传产品数量为：'.$num, PHP_EOL;
errorLog('所有商品全部上传完成，本次上传产品数量为：'.$num, 'finish');

function spuPrice($spuSn) {
	$spu	= json_encode(array(array('spu'=>$spuSn,"country"=>"Russian Federation","type"=>"1",'platform'=>'wish')));
	$url	= "http://price.valsun.cn/api.php?mod=distributorPrice&act=productPrice&spu=".$spu."&platform=wish&profit=0.0001&company_name=葛珊";
	try{
		$data	= file_get_contents($url);
	} catch (Exception $e) {
		return 0;
	}
	$data	= json_decode($data, true);
	$price	= array();
	foreach($data['data'] as $k => $v) {
		$price[] = $v['price'];
	}
	sort($price);
	$totalPrice	= round(end($price) - 1, 2);
	$totalPrice	= round(($totalPrice/(1-(12/100)-0.15))/(6.5) - 1, 2);
	return $totalPrice;
}

function getPrice($priceInfo, $skuData) {
	$sku	= explode('#', $skuData['sku']);
	$price	= $skuData['price'];
	foreach($priceInfo['data'] as $k => $v) {
		if($sku[0] === $v['sku']) {
			$price = round(($v['price']/(1-(10/100)-0.15))/(6.5) - 1, 2);	//10表示利润率, 6.5表示汇率
		}
	}
	return $price;
}

/**
 * 错误日志
 */
function errorLog($message,$type) {
	global $date;
	
	$path	= WEB_PATH.'log/uploadLog/'.date('Y-m').'/'.date('d').'/';	//$root.'/log/';
	if(!is_dir($path)) {
		$mkdir = mkdir($path,0777,true);
		if(!$mkdir) {
			exit('不能建立日志文件');
		}
	}
	$status = error_log(date("Y-m-d H:i:s")." {$message}\r\n",3,$path.$date.'_'.$type.'_success.log');
	return $status;
}