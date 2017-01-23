<?php
/**
 * 功能: 定时同步订单数量及状态
author: zxh
 * 日期: 2016/1/23 23:04
 */
include __DIR__.'/../common.php';

global $dbConn;

$_REQUEST['account'] = isset($argv[1]) ? $argv[1] : 'geshan0728';
$freshToken = getAccountToken($_REQUEST['account']);
$data = WishOrderAct :: act_wishOrderSync();
var_dump($data);
