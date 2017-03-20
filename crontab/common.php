<?php
error_reporting(E_ALL);
define("SYSTEM_CRONTAB_USER", "true"); //跳过所有权限验证
set_time_limit(0);
include substr(str_replace(DIRECTORY_SEPARATOR, '/', __DIR__), 0, stripos(__DIR__, 'crontab')) . "framework.php";
Core :: getInstance();
global $dbConn;

function sqlQuery($sql,$onlyQuery = false){
    global $dbConn,$sqls;
    $query = $dbConn->query($sql);
    $rs    = $dbConn->fetch_array_all($query);
    return $rs;
}