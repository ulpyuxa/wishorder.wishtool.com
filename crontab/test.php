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

$url	= 'http://token.valsun.cn/json.php?mod=api&act=reuqireWishAgentTokenByAccount&jsonp=1&account=geshan0728';

echo file_get_contents($url);exit;

$account = 'ulpyuxa';
echo $accountAbbr= C('ACCOUNTABBR')[$account];exit;


$price	= WishProductModel::spuPrice('YC001273');
echo $price;exit;

$str	= '';
echo TranslateModel::translator('Flowers');exit;

require_once WEB_PATH.'lib/sdk/wish/vendor/autoload.php';
use Wish\WishClient;
$client = new WishClient($access_token,'prod');
//$access_token = 'an_example_access_token';