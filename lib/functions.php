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