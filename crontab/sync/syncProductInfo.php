<?php
/**
 * 功能: 定时同步产品信息及产品状态到系统中
 * author: zxh
 * 日期: 2016/2/19 15:55
 */
error_reporting(E_ALL);
define("SYSTEM_CRONTAB_USER","true");	//跳过所有权限验证
set_time_limit(0);
include substr(str_replace(DIRECTORY_SEPARATOR, '/', __DIR__), 0, stripos(__DIR__, 'crontab'))."framework.php";
Core::getInstance();
global $dbConn;

$data	= WishProductModel::getWishProduct();
var_dump($data);