<?php
include_once WEB_PATH."lib/sdk/aliexpress/Aliexpress.php";
class uploadImageToBank extends Aliexpress {
	public function uploadImage($filePath,$fileName) {
		$this->apiName	= 'api.uploadImage';
		$this->rootpath	= "fileapi";
		$this->doInit();

		$picdata	= file_get_contents($filePath);
		//Log::write(strlen($picdata)."||".$filePath,"DEBUG");
		$accessToken	= $this->access_token;
		$apiInfo		= $this->server.'/'. $this->rootpath.'/'. $this->protocol.'/'. $this->version.'/'. $this->ns.'/'.$this->apiName.'/'. $this->appKey;
		$code_arr=	array (
			'access_token'		=> $accessToken,
			'fileName'			=> $fileName,
		);
		$url = $apiInfo."?".http_build_query($code_arr);
		return $this->request_post($url,$picdata);
	}
	public function request_post($remote_server,$content) { 
		$http_entity_type = 'application/x-www-from-urlencoded'; //发送的格式multipart/form-data, application/x-www-from-urlencoded
		$context = array( 
			'http'=>array( 
				'method'=>'POST', 
				 // 这里可以增加其他header..
				'header'=>"Content-type: " .$http_entity_type ."\r\n".'Content-length: '.strlen($content),
				'content'=>$content
			) 
		); 
		$stream_context =	stream_context_create($context); 
		$data	=	file_get_contents($remote_server,FALSE,$stream_context); 
		return $data; 
	}
}