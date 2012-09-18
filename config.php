<?php

return array(
	'sitename'	=> 'Indexer',
	'date'		=> 'j M Y',
	'root'		=> dirname(__FILE__),
	'ignore'	=> array('.','..','.htaccess','index.php','icon.php','class.folder.php',
						'error.log','customerror.log','Thumbs.db',$_SERVER['SCRIPT_FILENAME'],
						'css','img','js','config.php','class.config.php'), // ignore these files
);