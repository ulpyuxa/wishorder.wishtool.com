<?php
class WishProductModel {
	public static $dbConn;
	static $errCode	=	0;
	static $errMsg	=	"";

	public function __construct() {
	}
	
	public static function initDB() {
		global $dbConn;
		self::$dbConn = $dbConn;
	}
}