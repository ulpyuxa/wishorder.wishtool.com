<?php
if (!defined('WEB_PATH')) exit();

if(!defined("ACCOUNT_STATUS_OK")){
	define("ACCOUNT_STATUS_OK", 1);			//账户状态ok
	define("ACCOUNT_STATUS_NO_OK", 0);		//不可用的账号状态
	define("PLATFORM_EBAY", 1);				//ebay 平台
	define("PLATFORM_ALIEXPRESS", 2);		//速卖通平台
	define("PLATFORM_TMALL", 3);			//tmall平台
	
	define("ITEM_TYPE_AUCTION",1);			//拍卖
	define("ITEM_TYPE_FIXED_PRICE",2);		//固价
	define("ITEM_TYPE_VARIATIONS",3);		//多属性
}
 //水印需要的常量
if(!defined("THINKIMAGE_GD")){
	define('THINKIMAGE_GD',      1); //常量，标识GD库类型
	define('THINKIMAGE_IMAGICK', 2); //常量，标识imagick库类型

	/* 缩略图相关常量定义 */
	define('THINKIMAGE_THUMB_SCALING',   1); //常量，标识缩略图等比例缩放类型
	define('THINKIMAGE_THUMB_FILLED',    2); //常量，标识缩略图缩放后填充类型
	define('THINKIMAGE_THUMB_CENTER',    3); //常量，标识缩略图居中裁剪类型
	define('THINKIMAGE_THUMB_NORTHWEST', 4); //常量，标识缩略图左上角裁剪类型
	define('THINKIMAGE_THUMB_SOUTHEAST', 5); //常量，标识缩略图右下角裁剪类型
	define('THINKIMAGE_THUMB_FIXED',     6); //常量，标识缩略图固定尺寸缩放类型

	/* 水印相关常量定义 */
	define('THINKIMAGE_WATER_NORTHWEST', 1); //常量，标识左上角水印
	define('THINKIMAGE_WATER_NORTH',     2); //常量，标识上居中水印
	define('THINKIMAGE_WATER_NORTHEAST', 3); //常量，标识右上角水印
	define('THINKIMAGE_WATER_WEST',      4); //常量，标识左居中水印
	define('THINKIMAGE_WATER_CENTER',    5); //常量，标识居中水印
	define('THINKIMAGE_WATER_EAST',      6); //常量，标识右居中水印
	define('THINKIMAGE_WATER_SOUTHWEST', 7); //常量，标识左下角水印
	define('THINKIMAGE_WATER_SOUTH',     8); //常量，标识下居中水印
	define('THINKIMAGE_WATER_SOUTHEAST', 9); //常量，标识右下角水印
}
if(!defined('CURRENT_DATE')) {
	define('CURRENT_DATE', date('Ymd'));
}
//日志及debug信息记录配置
/*
return  array(
    'LOG_RECORD'	=>true,													// 开启日志记录
    'LOG_EXCEPTION_RECORD'  => true,										// 是否记录异常信息日志
    'LOG_LEVEL'		=>   'EMERG,ALERT,CRIT,ERR,WARN,NOTIC,INFO,DEBUG,SQL'	// 允许记录的日志级别
);*/
?>