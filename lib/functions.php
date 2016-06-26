<?php
	/**
	* 对添加的数据，转换html标签
	*/
	function newHtmlspecialchars($str)
	{
		switch(getType($str)){
			case 'boolean':
			case 'integer':
			case 'string':
			{
				//$str = mb_convert_encoding ($str,'UTF-8','GB2312,BIG5,ISO-8859-1');
				$str = htmlspecialchars($str,ENT_QUOTES ,'UTF-8');//•ENT_QUOTES 
				break;
			}
			case 'array':
			{
				$str = array_map('newHtmlspecialchars',$str);
				break;
			}
			case 'object':
			case 'resource':
			case NULL:
			default:
				//die('unknown type!');
		}
		return $str;
	}

	/**
	 * 功能: 日志记录
	 * $message
	 */
	function errorLog($message,$type, $file = '') {
		if(empty($file)) {		//如果没有将文件传输过来，则使用调用此方法的文件名
			$fileInfo	= get_required_files();
			$fileInfo	= explode('.', basename($fileInfo[0]));
			array_pop($fileInfo);
			$file		= implode('.', $fileInfo);
		}
		$path	= WEB_PATH.'log/'.$file.'/'.date('Y-m/d/');	//$root.'/log/';
		if(!is_dir($path)) {
			$mkdir = mkdir($path,0777,true);
			if(!$mkdir) {
				exit('不能建立日志文件');
			}
		}
		$status = error_log(date("Y-m-d H:i:s")." {$message}\r\n",3,$path.CURRENT_DATE.'_'.$type.'.log');
		return $status;
	}