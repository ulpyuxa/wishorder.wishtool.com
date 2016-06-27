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

function http($url, $data='', $method='GET'){   
    $curl = curl_init(); // 启动一个CURL会话  
    curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址  
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 对认证证书来源的检查  
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); // 从证书中检查SSL加密算法是否存在  
    curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.36'); // 模拟用户使用的浏览器  
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转  
    curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer  
    if($method=='POST'){  
        curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求  
        if ($data != ''){  
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包  
        }  
    }  
    curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环  
    curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容  
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回  
    $tmpInfo = curl_exec($curl); // 执行操作  
    curl_close($curl); // 关闭CURL会话  
    return $tmpInfo; // 返回数据  
}