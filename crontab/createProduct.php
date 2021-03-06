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
include __DIR__.'/../common.php';

global $dbConn;

$wishProductApi	= new WishProductApi('geshan0728', 1);

$logPath	= WEB_PATH.'log/productInfo/';
if(!is_dir($logPath)) {
	mkdir($logPath, 0777, true);
}

$newDir = WEB_PATH.'log/productInfo/'.date('Y/m-d').'/';
if(!is_dir($newDir)) {
	mkdir($newDir, 0777, true);
	if(!is_dir($newDir)) {
		exit('不能建立目录!');
	}
}

$dirDat	= file_get_contents('http://wishtool.valsun.cn/json.php?mod=apiWish&act=getlistingLog&jsonp=1&dir=1');
$dirDat	= json_decode($dirDat, true);
if(!isset($dirDat['data']) || empty($dirDat['data'])) {
	exit('未找到到数据');
}
foreach($dirDat['data'] as $k => $v) {
	$spuSn			= substr($v, 0, stripos($v, '.'));
	$productInfo	= file_get_contents('http://wishtool.valsun.cn/json.php?mod=apiWish&act=getlistingLog&jsonp=1&spuSn='.$spuSn);
	if(preg_match('/^\d+&/', $spuSn) && (strlen($spuSn) <= 8 || strlen($spuSn) >= 7)) {
		continue;
	}
	if(preg_match('/^(OS|AM|TT|MT|CB|DZ|WH)/i', $spuSn)) {
		continue;
	}
	$url			= 'http://api.fenxiao.valsun.cn/api.php?action=getDistributorOpenProducts&v=1.0&spu='.$spuSn.'&companyId=1553&platform=wish&warehouse=CN';
	$pushInfo		= file_get_contents($url);
	$pushInfo		= json_decode($pushInfo, true);
	if(!isset($pushInfo['data']) || empty($pushInfo['data'])) {		//此料号为未开放的料号不能刊登。
		continue;
	}
	$sql			= 'select spu from `ws_product` where spu = "'.$spuSn.'"';
	$query			= $dbConn->query($sql);
	$ret			= $dbConn->fetch_array_all($query);
	if(!empty($ret)) {	//已经上传过此商品
		echo $spuSn, ', 此料号已经上传过了，将跳过上传！', PHP_EOL;
		file_put_contents($newDir.$v, $productInfo."\n", FILE_APPEND);
		continue;
	}
	echo '现在处理可以刊登的料号，料号为：'.$spuSn, PHP_EOL;
	file_put_contents($logPath.$v, $productInfo, FILE_APPEND);	//将数据写入日志备用
	continue;//只将数据拉取回来再进行操作。
}

echo '全部listing拉取完成！';