<?php
/**
 * 功能: 测试国际包裹跟踪号
author: zxh
 * 日期: 2016/1/23 23:04
 */
error_reporting(E_ALL);
define("SYSTEM_CRONTAB_USER", "true"); //跳过所有权限验证
set_time_limit(0);
include substr(str_replace(DIRECTORY_SEPARATOR, '/', __DIR__), 0, stripos(__DIR__, 'crontab')) . "framework.php";
Core :: getInstance();
global $dbConn;

include WEB_PATH.'lib/track.class.php';
$track = new Trackingmore;
$trackingNumber = 'RL181781276CN';
$trackInfo = $track->getSingleTrackingResult('china-post', $trackingNumber);
var_dump($trackInfo);