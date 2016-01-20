<?php
/**
 * 功能: mysql访问底层类
 * 版本: v2.0
 * 
 * 修改历史:
 * v2.0		修改为mysqli类
 */
class mysqliDB{
	var $version = '';
	var $querynum = 0;
	var $link = null;

	function connect($dbhost, $dbuser, $dbpw, $dbname = '', $pconnect = 0, $halt = TRUE, $dbcharset2 = '') {
		if(!empty($pconnect)) {
			$socketPath = get_cfg_var('mysqli.default_socket');
			$this->link = mysqli_connect('p'.$dbhost, $dbuser, $dbpw, $dbname, '3306', $socketPath);
		} else {
			$this->link = mysqli_connect($dbhost, $dbuser, $dbpw);
		}
		if(!$this->link) {
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

	function select_db($dbname) {	//完成
		return mysqli_select_db($this->link, $dbname);
	}

	function fetch_array($query, $result_type = MYSQL_ASSOC) {		//完成
		return mysqli_fetch_array($query, $result_type);
	}

	function fetch_array_all($query, $result_type = MYSQL_ASSOC){	//完成
		$arr	=	array();
		while(1 && $ret	=	mysqli_fetch_array($query, $result_type)){
			$arr[]	=	$ret;	
		}
		return $arr;
	}

	function fetch_first($sql) {		//完成
		return $this->fetch_array($this->query($sql));
	}

	function result_first($sql) {		//完成
		return $this->result($this->query($sql), 0);
	}

	function query($sql, $type = '') {		//完成
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

	function affected_rows() {		//完成
		return mysqli_affected_rows($this->link);
	}

	function error() {
		return (($this->link) ? mysqli_error($this->link) : mysqli_error());
	}

	function errno() {		//完成
		return intval(($this->link) ? mysqli_errno($this->link) : mysqli_errno());
	}

	function result($query, $row = 0) {
		$query = @mysqli_result($query, $row);
		return $query;
	}

	function num_rows($query) {		//完成
		$query = mysqli_num_rows($query);
		return $query;
	}

	function num_fields($query) {	//完成
		return mysqli_num_fields($query);
	}

	function free_result($query) {	//完成
		return mysqli_free_result($query);
	}

	function insert_id() {		//完成
		return ($id = mysqli_insert_id($this->link)) >= 0 ? $id : $this->result($this->query("SELECT last_insert_id()"), 0);
	}

	function fetch_row($query) {	//完成
		$query = mysqli_fetch_row($query);
		return $query;
	}

	function fetch_assoc($query) {
		$query = mysqli_fetch_assoc($query);
		return $query;
	}
	function fetch_fields($query) {		//完成
		return mysqli_fetch_field($query);
	}

	function version() {	//改造完成
		if(empty($this->version)) {
			$this->version = mysqli_get_server_version($this->link);
		}
		return $this->version;
	}

	function close() {	//改造完成
		return mysqli_close ($this->link);
	}

	function halt($message = '', $sql = '') {	//改造完成
		if(!empty($sql)){
			$errorStr	=	"message : ".$message. ", sql: ".$sql."\r\n";
		}else{
			$errorStr	=	"message : ".$message."\r\n";
		}
		Log::write($errorStr,Log::ERR);
		throw new Exception($message);
	}
	
	
	
	/*************************
	 * 事务支持(必须是inodb或ndb引擎)
	 */
	function begin(){
		$this->query("SET AUTOCOMMIT=0");
		$this->query("BEGIN");
	}
	
	function commit(){
		$this->query("COMMIT");
	}
	
	function rollback(){
		$this->query("ROLLBACK");
	}
	

	function ping(){
		
		return mysqli_ping($this->link);
	}
}