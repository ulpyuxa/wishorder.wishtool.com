<?php
class AliexListingModel {
	public static $dbConn;
	static $errCode	=	0;
	static $errMsg	=	"";

	public function __construct() {
		self::initDB();
	}
	
	public static function initDB() {
		global $dbConn;
		self::$dbConn = $dbConn;
	}

	public static function getOrderData() {
		$sql	= 'SELECT * FROM `ws_order`';
		$query	= self::$dbConn->query($sql);
		$ret	= self::$dbConn->fetch_array_all($query);
		return $ret;
	}
}