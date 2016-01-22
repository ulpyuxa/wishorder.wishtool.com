<?php
class AliexListingModel {
	public static $dbConn;
	static $errCode	=	0;
	static $errMsg	=	"";

	public function __construct() {
	}
	
	public static function initDB() {
		global $dbConn;
		self::$dbConn = $dbConn;
	}

	public static function getListingData() {
		self::initDB();

		$sql	= 'SELECT * FROM `wish_account`';
		$query	= self::$dbConn->query($sql);
		$ret	= self::$dbConn->fetch_array_all($query);
		return $ret;
	}
}