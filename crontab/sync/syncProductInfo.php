<?php
/**
 * 功能: 定时同步产品信息及产品状态到系统中
 * author: zxh
 * 日期: 2016/2/19 15:55
 * http://token.valsun.cn/json.php?mod=api&act=reuqireWishAgentTokenByAccount&jsonp=1&account=geshan0728
 */
include __DIR__.'/../common.php';

global $dbConn;

$since				= '';
$_REQUEST['page']	= 1;
$_REQUEST['account']= isset($argv[1]) ? $argv[1] : 'geshan0728';
$freshToken = getAccountToken($_REQUEST['account']);
if(!$freshToken) {
	exit('token更新失败！');
}
echo $_REQUEST['account'].' token更新成功', PHP_EOL;
$ret	= WishProductModel::productList();
if(!empty($ret)) {
	$since = date('Y-m-d', strtotime('-2 days'));
}
$since="";
$data	= WishProductModel::getWishProduct(0, 50, $since);
var_dump($data);