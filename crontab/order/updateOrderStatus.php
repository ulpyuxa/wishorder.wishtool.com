<?php
/**
 * 功能: 定时同步订单数量及状态
 * author: zxh
 * 日期: 2016/1/23 23:04
 */
error_reporting(E_ALL);
define("SYSTEM_CRONTAB_USER","true");	//跳过所有权限验证
set_time_limit(0);

echo str_replace(DIRECTORY_SEPARATOR, '/', __DIR__),PHP_EOL;
echo rtrim(str_replace(DIRECTORY_SEPARATOR, '/', __DIR__), 'crontab/order')."/framework.php";exit;
include rtrim(str_replace(DIRECTORY_SEPARATOR, '/', __DIR__), 'crontab/order')."/framework.php";
Core::getInstance();
global $dbConn;

$data	= WishProductModel::getWishProduct();
var_dump($data);