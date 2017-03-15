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
include __DIR__.'/common.php';

$sql	= 'select * from ws_product';
$query	= $dbConn->query($sql);
$ret	= $dbConn->fetch_array_all($query);
foreach($ret as $k => $v) {
	$url	= 'http://api.fenxiao.valsun.cn/api.php?action=getDistributorOpenProducts&v=1.0&spu='.$v['spu'].'&companyId=1553&platform=wish&warehouse=CN';
	$data	= file_get_contents($url);
	$data	= json_decode($data, true);
	if(empty($data['data'])) {
		echo $v['spu'], PHP_EOL;
	}
}
echo '完成';