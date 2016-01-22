<?php
if (!defined('WEB_PATH')) exit();

//全局配置信息
return  array(
	//运行相关
	"RUN_LEVEL"		=>	"DEV",		//	运行模式。 DEV(开发)，GAMMA(测试)，IDC(生产)

	//日志相关
	"LOG_RECORD"	=>	true,		//	开启日志记录
	"LOG_TYPE"		=>	3,			//	1.mail  2.file 3.api
	"LOG_PATH"		=>	WEB_PATH."/log/",	//文件日志目录
	"LOG_FILE_SIZE"	=>	2097152,
	"LOG_DEST"		=>	"",			//	日志记录目标
	"LOG_EXTRA"		=>	"",			//	日志记录额外信息

	//数据接口相关
	"DATAGATE"		=>	"db",		//	数据接口层 cache, db, socket
	"DB_TYPE"		=>	"mysqli",	//	mysql	mssql	postsql	mongodb		
	

	//mysql db	配置
	"DB_CONFIG"		=>	array(
		"master1"	=>	array('HOST' => 'localhost', 'USER' => 'www', 'PASS' => '123456', 'PORT' => '3306', 'DBNAME' => 'zxh_wish'),			//主DB
		//"master1"	=>	array('HOST' => SAE_MYSQL_HOST_M, 'USER' => SAE_MYSQL_USER, 'PASS' => SAE_MYSQL_PASS, 'PORT' => SAE_MYSQL_PORT, 'DBNAME' => SAE_MYSQL_DB)			//主DB
		//"slave1"	=>	array("localhost","root","","3306")		//从DB
	),
	'OPENTOKEN'	=> '5f5c4f8c005f09c567769e918fa5d2e3',
	/**图片系统相关变量**/
);

?>
