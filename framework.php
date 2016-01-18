<?php
class Core {
    private static $_instance = array();
	private static $classFile;

	private function __construct(){
		//启动SESSION
		@session_start();
		//-----------需要页面显示调试信息,	注释掉下面两行即可---
		
		//-------------------------------------------------------
		$env	=	is_string(get_cfg_var("platform.env")) ? get_cfg_var("platform.env") : '';
		if(!empty($env)) {
			define('ENV', $env);
		}
        date_default_timezone_set("Asia/Shanghai");
		if(version_compare(PHP_VERSION,'5.4.0','<') ) {
			@set_magic_quotes_runtime (0);
			define('MAGIC_QUOTES_GPC',get_magic_quotes_gpc()?True:False);
		}

        if(!defined('WEB_PATH')){
			define("WEB_PATH", __DIR__.'/');
		}
		include	WEB_PATH."lib/log.php";

		//注册处理脚本中出现错误的函数
		set_error_handler(array("Core",'appError'));
		set_exception_handler(array("Core",'appException'));

		include	WEB_PATH."lib/common.php";

		//加载全局配置信息
		C(include WEB_PATH.(defined('ENV') ? 'conf/common_'.ENV.'.php' : "conf/common.php"));
		include	WEB_PATH."conf/constant.php";
		include	WEB_PATH."lib/auth.php";	//鉴权
		//Auth::setAccess(include WEB_PATH.'conf/access.php');
		

		//加载数据接口层及所需支撑
		include	WEB_PATH."lib/cache/cache.php";	//cache
		include	WEB_PATH."lib/service/http.php";	//网络接口	
		include	WEB_PATH."lib/functions.php";
		if(C("DATAGATE") == "db"){
			$db	=	C("DB_TYPE");
			include	WEB_PATH."lib/db/".$db.".php";	//db直连
			if($db	==	"mysql"){
				global	$dbConn;
				$db_config	=	C("DB_CONFIG");
				$dbConn	=	new mysql();
				$dbConn->connect($db_config["master1"]['HOST'],$db_config["master1"]['USER'],$db_config["master1"]['PASS']);
				$dbConn->select_db($db_config["master1"]['DBNAME']);
			}
		}
		
		//自动加载类
		 spl_autoload_register(array('Core', 'autoload'));
	}


	//自动加载实现
	public function autoload($class){
		//加载act
		if(strpos($class,"Act")){
			$name	=	preg_replace("/Act/","",$class);
			$fileName	=	lcfirst($name).".action.php";
			Core::getFile($fileName,WEB_PATH."action/");
			if(empty(Core::$classFile)){
				exit('action not exits');
			}
			include_once Core::$classFile;
		}
		
		if(strpos($class,"Model")){
			$name	=	preg_replace("/Model/","",$class);
			$fileName	=	lcfirst($name).".model.php";
			Core::getFile($fileName,WEB_PATH."model/");
			if(empty(Core::$classFile)){
				exit('Model not exits');
			}
			include_once Core::$classFile;
		}
		
		if(strpos($class,"View")){
			$name	=	preg_replace("/View/","",$class);
			$fileName	=	lcfirst($name).".view.php";
			Core::getFile($fileName,WEB_PATH."view/");			
			if(empty(Core::$classFile)){
				exit('View not exits');
			}
			include_once Core::$classFile;
		}
	}
	
	public static function getFile($fileName,$path){ 
		if ($handle = @opendir($path)) { 
		    while(false !== ($file = @readdir($handle))) {
		        if(is_dir($path.$file) && ($file != "." && $file != "..")){ 
		        	Core::getFile($fileName,$path.$file."/");
		        }else{
		       	 	if($file === $fileName) {
		        		Core::$classFile	=	$path.$file;
		        	}
		        }
		    }
		}
		@closedir($handle);
	}
	
	private function __clone() {}
	
	//单实例
    public static function getInstance(){
        if(!(self::$_instance instanceof self)){
            self::$_instance = new self();
        }
        return self::$_instance;
    }


    /**
     +----------------------------------------------------------
     * 自定义异常处理
     * @access public
     * @param mixed $e 异常对象
     */
    static public function appException($e) {
		echo $e;
        //halt($e->__toString());
    }

    /**
     +----------------------------------------------------------
     * 自定义错误处理
     * @access public
     * @param int $errno 错误类型
     * @param string $errstr 错误信息
     * @param string $errfile 错误文件
     * @param int $errline 错误行数
     * @return void
     +----------------------------------------------------------
     */
    static public function appError($errno, $errstr, $errfile, $errline) {
		//echo $errno;
		switch ($errno) {
			case E_WARNING:
				$errorStr = "[$errno] $errstr ".basename($errfile)." 第 $errline 行.";
				if(C('LOG_RECORD')) Log::write($errorStr,Log::ERR);
				echo ($errorStr)."<br>"."<br>";
				break;
			case E_ERROR:
			case E_USER_ERROR:
				$errorStr = "[$errno] $errstr ".basename($errfile)." 第 $errline 行.";
				if(C('LOG_RECORD')) Log::write($errorStr,Log::ERR);
				echo($errorStr)."<br>"."<br>";
				break;
			case E_STRICT:
			case E_USER_WARNING:
			case E_USER_NOTICE:
			default:
				$errorStr = "[$errno] $errstr ".basename($errfile)." 第 $errline 行.";
				Log::record($errorStr,Log::NOTICE);
				//echo $errorStr, PHP_EOL, '<br /><br />';
				break;
		}
    }
}
