<?php
class File {
	public static function send($code) {
		$key	= Config::get('key');
		$file	= Config::get('root') . DS . urldecode(Encrypt::decode($code, $key));

		header('Content-Disposition: attachment; filename="' . basename($file) .'"');
		header('Content-type: application/octet-stream');
		
		readfile($file);
		
		exit;	
	}
}
		
?>