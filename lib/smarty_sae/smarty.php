<?php
include 'libs/Smarty.class.php';
class AppSmarty extends Smarty{
	public function __construct(){
		parent::__construct();

		//$this->cache_lifetime = 30*24*3600; //更新周期
		$this->template_dir		= WEB_PATH.'html/templates';
		$this->compile_dir		= !defined('ENV') ? 'saemc://smartytpl/' : WEB_PATH.'lib/smarty_sae/template_c/';
		$this->cache_dir		= !defined('ENV') ? 'saemc://smartytpl/' : WEB_PATH.'lib/smarty_sae/cache/';
		$this->compile_locking	= false; // 防止调用touch,saemc会自动更新时间，不需要touch
		$this->caching			= false; //是否使用缓存，项目在调试期间，不建议启用缓存

		/*$this->debugging		= true;
		$this->caching			= true;
		$this->cache_lifetime	= 120;*/
	}
}