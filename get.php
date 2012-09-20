<?php
include_once(dirname(__FILE__) . '/class.config.php');
include_once(dirname(__FILE__) . '/class.encrypt.php');
// load configs
Config::load();

$file = isset($_GET['file']) ? $_GET['file'] : false;

if($file == false) {
	exit("File not found.");
}

$key	= Config::get('key');
$file	= Config::get('root') . DS . urldecode(Encrypt::decode($file, $key));

header('Content-Disposition: attachment; filename="' . basename($file) .'"');
header('Content-type: application/octet-stream');
readfile($file);
		
?>