<?php
/**
 * 功能: mysql访问底层类
 * 版本: v2.0
 * 
 * 修改历史:
 * v2.0		修改为mysqli类
 * v2.1		修改事务处理部分，删除无用注释
 */
class mysqliDB{
	var $version = '';
	var $querynum = 0;
	var $link = null;

	function connect($dbhost, $dbuser, $dbpw, $dbname = '', $pconnect = 0, $halt = TRUE, $dbcharset2 = '') {
		if(!$this->link = mysqli_connect($dbhost, $dbuser, $dbpw)) {
			$halt && $this->halt('Can not connect to MySQL server');
		} else {
			if($this->version() > '4.1') {
				global $charset, $dbcharset;
				$dbcharset = $dbcharset2 ? $dbcharset2 : $dbcharset;
				$dbcharset = !$dbcharset && in_array(strtolower($charset), array('gbk', 'big5', 'utf-8')) ? str_replace('-', '', $charset) : $dbcharset;
				$serverset = $dbcharset ? 'character_set_connection='.$dbcharset.', character_set_results='.$dbcharset.', character_set_client=binary' : '';
				$serverset .= $this->version() > '5.0.1' ? ((empty($serverset) ? '' : ',').'sql_mode=\'\'') : '';
				$serverset && mysqli_query($this->link, "SET $serverset");
			}
			$dbname && @mysqli_select_db($this->link, $dbname);
		}
		@mysqli_query($this->link, "set names utf8");
	}

	function select_db($dbname) {
		return mysqli_select_db($this->link, $dbname);
	}

	function fetch_array($query, $result_type = MYSQLI_ASSOC) {
		return mysqli_fetch_array($query, $result_type);
	}

	function fetch_array_all($query, $result_type = MYSQLI_ASSOC){
		$arr	=	array();
		while(1 && $ret	=	mysqli_fetch_array($query, $result_type)){
			$arr[]	=	$ret;	
		}
		return $arr;
	}

	function fetch_first($sql) {
		return $this->fetch_array($this->query($sql));
	}

	function result_first($sql) {
		return $this->result($this->query($sql), 0);
	}

	function query($sql, $type = '') {
		global $debug, $discuz_starttime, $sqldebug, $sqlspenttimes;
		if(defined('SYS_DEBUG') && SYS_DEBUG) {
			@include_once DISCUZ_ROOT.'./include/debug.func.php';
			sqldebug($sql);
		}

		$func = $type == 'UNBUFFERED' && @function_exists('mysql_unbuffered_query') ?
			'mysql_unbuffered_query' : 'mysqli_query';
		if(!($query = $func($this->link, $sql))) {
			if(in_array($this->errno(), array(2006, 2013)) && substr($type, 0, 5) != 'RETRY') {
				$this->close();
				$db_config	=	C("DB_CONFIG");
				$this->connect($db_config["master1"][0],$db_config["master1"][1],$db_config["master1"][2],$db_config["master1"][4]);
				return $this->query($sql, 'RETRY'.$type);
			} elseif($type != 'SILENT' && substr($type, 5) != 'SILENT') {
				$this->halt('MySQL Query Error', $sql);
			}
		}

		$this->querynum++;
		return $query;
	}

	function affected_rows() {
		return mysqli_affected_rows($this->link);
	}

	function error() {
		return (($this->link) ? mysqli_error($this->link) : mysqli_error());
	}

	function errno() {
		return intval(($this->link) ? mysqli_errno($this->link) : mysqli_errno());
	}

	function result($query, $row = 0) {
		$query = @mysqli_result($query, $row);
		return $query;
	}

	function num_rows($query) {
		$query = mysqli_num_rows($query);
		return $query;
	}

	function num_fields($query) {
		return mysqli_num_fields($query);
	}

	function free_result($query) {
		return mysqli_free_result($query);
	}

	function insert_id() {
		return ($id = mysqli_insert_id($this->link)) >= 0 ? $id : $this->result($this->query("SELECT last_insert_id()"), 0);
	}

	function fetch_row($query) {
		$query = mysqli_fetch_row($query);
		return $query;
	}

	function fetch_assoc($query) {
		$query = mysqli_fetch_assoc($query);
		return $query;
	}
	function fetch_fields($query) {
		return mysqli_fetch_field($query);
	}

	function version() {
		if(empty($this->version)) {
			$this->version = mysqli_get_server_version($this->link);
		}
		return $this->version;
	}

	function close() {
		return mysqli_close ($this->link);
	}

	function halt($message = '', $sql = '') {
		if(!empty($sql)){
			$errorStr	=	"message : ".$message. ", sql: ".$sql."\r\n";
		}else{
			$errorStr	=	"message : ".$message."\r\n";
		}
		Log::write($errorStr,Log::ERR);
		throw new Exception($message);
	}
	
	
	
	/*************************
	 * 事务开始(必须是inodb或ndb引擎)
	 */
	function begin(){
		$this->query("SET AUTOCOMMIT=0");
		$this->autocommit(false);
	}

	/**
	 * 设置是否自动提交
	 */
	function autocommit($mode) {
		return mysqli_autocommit($this->link, $mode);
	}
	
	/**
	 * 提交事务
	 */
	function commit(){
		mysqli_commit($this->link);
		$this->autocommit(true);
	}
	/**
	 * 事务回滚
	 */
	function rollback(){
		mysqli_rollback($this->link);
		return $this->autocommit(true);
	}
	/*****************事务处理*****************/

	function ping(){
		return mysqli_ping($this->link);
	}
}
