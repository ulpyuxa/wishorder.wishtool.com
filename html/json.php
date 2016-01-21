<?php
error_reporting(E_ALL);
header("Content-type: text/html; charset=utf-8");
date_default_timezone_set('Asia/Shanghai');
include "../index.php";
Core::getInstance();

$mod	=	isset($_REQUEST['mod']) ? $_REQUEST['mod']: "";
$act	=	isset($_REQUEST['act']) ? $_REQUEST['act']: "";
//$token	=	trim($_REQUEST['token']);

if(empty($mod)){
	echo "empty mod";
	exit;
}

if(empty($act)){
	echo "empty act";
	exit;
}
//print_r($_POST);exit;
//鉴权验证
//$auth	=	new Auth();
//$auth->loginByToken($token);

$modFile	=	WEB_PATH."action/".$mod.".action.php";
//TODO 过滤特殊字符
if(file_exists($modFile)){
	include $modFile;
}else{
	echo "file no exists!!";
	echo $modFile;
	exit;
}

$modName	=	ucfirst($mod."Act");
$modClass	=	new $modName();

$actName	=	"act_".$act;
//var_dump($modFile);exit;

//鉴权验证
//$auth	=	new Auth();
//$auth->loginByToken($token);
if(isset($_REQUEST['token']) or isset($_SESSION['userToken']))
{
	//$token = Auth::checkUrl($_REQUEST,$_SERVER);
	//当用户通过url访问
	if(isset($_REQUEST['token'])) {
		$_SESSION['userToken'] = $_REQUEST['token'];
	}
	if(!isset($_SESSION['userToken']) or strlen($_SESSION['userToken']) < 10) {
			$_SESSION['userToken'] = "8trhsz5ds54a1ga4gs51fga4er1zxv15";
	}
	if(!isset($_SESSION['userToken'])) {
		$ret = array('errCode' => '7006', 'errMsg' => '请登陆系统再试！', 'data' => '');
	}
	$status = Auth::checkAccess($modName,$actName);
	if(!$status) {
		$ret = array('errCode' => '7005', 'errMsg' => 'token验证未通过！', 'data' => '');
	}
}

if(method_exists($modClass, $actName)){
	$ret	=	$modClass->$actName();
}else{
	echo "no this act!!";
}

$callback	=	isset($_REQUEST['callback']) ? $_REQUEST['callback']: "";
$jsonp		=	isset($_REQUEST['jsonp']) ? $_REQUEST['jsonp']: "";

$dat	=	array();
if(empty($ret)){
	$dat	=	array("errCode"=>$modName::$errCode, "errMsg"=>$modName::$errMsg, "data"=>"");
}else{
	$dat	=	array("errCode"=>$modName::$errCode, "errMsg"=>$modName::$errMsg, "data"=>$ret);
}

if(!empty($callback)){
	if(!empty($jsonp)){
		echo "try{ ".$callback."(".json_encode($dat)."); }catch(){alert(e);}";
	}else{
		echo $callback."(".json_encode($dat).");";
	}
	
}else{
	if(!empty($jsonp)){
		echo json_encode($dat);
	}else{
		echo $dat;
	}
}
?>