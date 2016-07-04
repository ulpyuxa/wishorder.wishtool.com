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

$spuArr	= isset($argv[1]) ? explode(',', $argv[1]) : array();

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
if(!is_dir($errorDir)) {
	mkdir($errorDir, 0777, true);
	if(!is_dir($errorDir)) {
		exit('不能建立目录!');
	}
}
$wishProductApi	= new WishProductApi('geshan0728', 1);
$num		= 0;
$uploadNum	= rand(10, 20);
foreach($files as $fileKey => $fileVal) {
	$spuInfo		= explode('.', $fileVal);
	$spuSn			= $spuInfo[0];
	if(!empty($spuArr)) {
		if(!in_array($spuSn, $spuArr)) {
			continue;
		}
	}
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
		echo '价格小于0.1', PHP_EOL;
		rename($logPath.$fileVal, $errorDir.$spuSn.'.log');
		continue;
	}
	
	$productInfo	= readProductInfo($spuSn);
	var_dump($productInfo);exit;
	if(empty($productInfo)) {
		echo '没有数据', PHP_EOL;
		rename($logPath.$fileVal, $errorDir.$spuSn.'.log');
		continue;
	}
	if($num > $uploadNum) {	//每天上传100个
		break;
	}
	$data			= json_decode($productInfo, true);
	$data			= explode("\n", $data['data']);
	$spuData		= json_decode($data[0], true);
	$tags			= explode(',',$spuData['tags']);
	if(count($tags) < 5) {		//如果tags数量小于5个，则不上传
		echo $spuSn.'的tags数量小于5个', PHP_EOL;
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
	$spuData['name']		= trim($nameInfo[0]).' P28d';
	echo $spuSn, PHP_EOL;
	$spuData['main_image']		= imageReplace($spuData['main_image']);
	$spuData['extra_images']	= imageReplace($spuData['extra_images']);
	$spuStatus				= $wishProductApi->createProductSpu($spuData);
	if(!empty($skuData)) {		//单料号没有子料号，所以不用进来
		foreach($skuData as $skuKey => $skuVal) {
			//print_r($skuVal);
			$skuStatus = $wishProductApi->createProductSku($skuVal);
			//var_dump($skuStatus);
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

function imageReplace($images) {
	$images	= explode('|', $images);
	foreach($images as $imagesKey => $imagesVal) {
		preg_match('/\/v\d+/i', $imagesVal, $arr);	//获取版本号，以第一个位置的url为准;
		$imgVer		= intval(substr($arr[0], 2, strlen($arr[0])));	//url路径中的版本号
		$imgInfo	= explode('.', $imagesVal);
		$imgName	= explode('-', basename($imagesVal));
		array_pop($imgName);
		if(strlen($imgVer) > 0) {
			$images[$imagesKey]	= 'http://images.wishtool.cn/v'.$imgVer.'/'.implode('-', $imgName).'-zxhTest.'.end($imgInfo);
		} else {
			$images[$imagesKey]	= 'http://images.wishtool.cn/'.implode('-', $imgName).'-zxhTest.'.end($imgInfo);
		}
	}
	return implode('|', $images);
}

function readProductInfo($spu) {
	global $logPath;
	$data		= file_get_contents($logPath.$spu.'.log');
	$sec		= stripos($data, '{"errCode', 50);
	$dataArr	= explode('{"errCode', $data);
	$hasTags	= false;
	foreach($dataArr as $k => $v) {
		if(strlen($v) < 100) {
			continue;
		}
		$json		= json_decode('{"errCode'.$v, true);
		$ret		= explode("\n", $json['data']);
		foreach($ret as $retKey	=> $retVal) {
			$spuData	= json_decode($ret[0], true);
			$tags		= explode(',',$spuData['tags']);
			if(count($tags) > 5) {
				$hasTags	= '{"errCode'.$v;
				break;
			}
		}
		if(strlen($hasTags) > 0) {
			break;
		}
	}
	return $hasTags;
}