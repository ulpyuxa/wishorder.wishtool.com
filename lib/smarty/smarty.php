<?php
include 'libs/Smarty.class.php';
class AppSmarty extends Smarty{
	public function __construct(){
		parent::__construct();
	
		//$this->cache_lifetime = 30*24*3600; //更新周期
		$this->caching			= false; //是否使用缓存，项目在调试期间，不建议启用缓存
		$this->setTemplateDir(WEB_PATH.'html/tpmplates'); //设置模板目录
		if(!defined('ENV')) {
			mkdir('saemc://templates_c/');
		}
		$this->setCompileDir(defined('ENV') ? WEB_PATH.'lib/smarty/templates_c' : 'saemc://templates_c/'); //设置编译目录
		$this->setCacheDir(defined('ENV') ? WEB_PATH.'lib/smarty/cache' : 'saemc://templates_c/'); //缓存文件夹
		$this->setUseSubDirs(false);   //子目录变量（是否在缓存文件夹中生成子目录）
		$this->setCompileLocking(false);
		$this->setLeftDelimiter('{');
		$this->setRightDelimiter('}');
		$this->setDebugging(true);
	}
}