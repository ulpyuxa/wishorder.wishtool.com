<?php
/**
 * 功能: 将product目录下面的数据写入数据库进行管理
 * author: zxh
 * 日期: 2016/7/4 22:48
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
foreach($files as $fileKey => $fileVal) {
	$spuInfo		= explode('.', $fileVal);
	$spuSn			= $spuInfo[0];
	if(!empty($spuArr)) {
		if(!in_array($spuSn, $spuArr)) {
			echo $spuSn,'：没有料号信息', PHP_EOL;
			continue;
		}
	}
	errorLog('开始上传,'.$spuSn, 'tip');
	$sql	= 'select spuSn from `ws_wait_publish` where spuSn = "'.$spuSn.'"';
	$query	= $dbConn->query($sql);
	$ret	= $dbConn->fetch_array_all($query);
	if(!empty($ret)) {	//已经上传过此商品
		echo $spuSn, ', 此料号已经记录到数据库中，不需要再记录', PHP_EOL;
		//rename($logPath.$fileVal, $errorDir.$spuSn.'.log');
		continue;
	}
	$price = spuPrice($spuSn);
	
	$productInfo	= readProductInfo($spuSn);
	if(empty($productInfo)) {
		echo '没有数据', PHP_EOL;
		rename($logPath.$fileVal, $errorDir.$spuSn.'.log');
		continue;
	}

	$data			= json_decode($productInfo, true);
	$data			= explode("\n", $data['data']);
	$spuData		= json_decode($data[0], true);
	$tags			= explode(',',$spuData['tags']);
	unset($data[0], $spuData['key']);
	array_pop($data);
	$skuData	= array();

	$spuData['upc']			= '';
	$spuSku					= explode("#", $spuData['sku']);
	$spuParentSku			= explode("#", $spuData['parent_sku']);
	$nameInfo				= explode("#", $spuData['name']);
	$spuData['sku']			= $spuSku[0].'#P28d';
	$spuData['price']		= $price;
	$spuData['shipping']	= 1;	//默认运费是$1
	$spuData['parent_sku']	= $spuParentSku[0].'#P28d';
	$spuData['name']		= trim($nameInfo[0]).' P28d';
	$spuData['main_image']		= imageReplace($spuData['main_image']);
	$spuData['extra_images']	= imageReplace($spuData['extra_images']);
	preg_match('/\/v\d+/i', $spuData['main_image'], $arr);	//获取版本号，以第一个位置的url为准;
	$imgVer		= intval(substr($arr[0], 2, strlen($arr[0])));	//url路径中的版本号
	$insertData	= array(
		'spuSn'			=> $spuSn,
		'main_image'	=> basename($spuData['main_image']),
		'name'			=> mysqli_real_escape_string($dbConn->link, $spuData['name']),
		'price'			=> $price,
		'shipping'		=> 1,
		'photoVersion'	=> $imgVer,
		'shipping_time'	=> $spuData['shipping_time'],
		'tags'			=> mysqli_real_escape_string($dbConn->link, $spuData['tags']),
	);
	$sql	= 'insert into ws_wait_publish (`'.implode('`, `', array_keys($insertData)).'`) values ("'.implode('", "', $insertData).'")';
	$query	= $dbConn->query($sql);
	echo $spuSn, PHP_EOL;
	$num++;
}

echo '所有商品已经全部记录到数据库中，总数量：'.$num, PHP_EOL;

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
	if(count($dataArr) === 1) {
		return $data;
	} 
	foreach($dataArr as $k => $v) {
		if(strlen($v) < 100) {
			continue;
		}
		$json		= json_decode('{"errCode'.$v, true);
		$ret		= explode("\n", $json['data']);
		foreach($ret as $retKey	=> $retVal) {
			$spuData	= json_decode($ret[0], true);
			$tags		= explode(',',$spuData['tags']);
			$hasTags	= '{"errCode'.$v;
		}
		if(strlen($hasTags) > 5) {
			break;
		}
	}
	return $hasTags;
}