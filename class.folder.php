<?php

include_once(dirname(__FILE__) . '/class.config.php');
include_once(dirname(__FILE__) . '/class.encrypt.php');

class Folder {
	
	/*
	 * total files count
	 * 
	 * @access  public
	 */
	public $count = 0;
	
	
	/*
	 * total files size
	 * 
	 * @access  public
	 */
	public $size = 0;
	
	
	/*
	 * Files array. Use getFiles() to get it.
	 * 
	 * @access  private
	 */
	private $files = array();
	
	
	/*
	 * Directories array. Use getDirs() to get it.
	 * 
	 * @access  private
	 */
	private $dirs = array();
	
	
	/*
	 * Current dir's name
	 * 
	 * @access  public
	 */
	public $name = null;
	
	
	/*
	 * Current dir. Only set if in inside a sub-directory
	 * 
	 * @access  public
	 */
	public $current = null;
	
	
	/*
	 * Up directory. Use getUpURL() for the url
	 * 
	 * @access  public
	 */
	private $up_dir = null;
	
	/*
	 * constructor
	 * 
	 * @access  public
	 * @return  void
	 */
	public function __construct() {
		// load configs
		Config::load();
		
		$root		= Config::get('root');
		$key		= Config::get('key');
		$dir		= isset($_GET['dir']) ? Encrypt::decode($_GET['dir'], $key) : '';
		
		if(strstr($dir,'..'))
		{	
			$dir='';
		}

		$path			= "$root/$dir/";
		$folder			= $this->openDir($dir);
		$this->dirs		= $folder['dirs'];
		$this->files	= $folder['files'];
		
		$this->name 	= basename($path);
		$this->current	= $dir;
		$this->up_dir	= dirname($dir);
	}
	
	/*
	 * Open and traverse into a directory
	 * 
	 * @access  public
	 * @param  String  $path  Absolute folder path
	 * @return  Array  Array of files and folders
	 */
	private function openDir($openDir) {
		$dirs	= $files = array();
		$ignore	= Config::get('ignore');
		$root	= Config::get('root');
		$key	= Config::get('key');
		$dir	= isset($_GET['dir']) ? Encrypt::decode($_GET['dir'], $key) : '';
		$search	= isset($_GET['search']) ? $_GET['search'] : false;
		$deep	= isset($_GET['deep']) ? (boolean) $_GET['deep'] : false;
		$path	= rtrim($root . DS . $openDir, DS) . DS;
		
		if(!is_dir($path) || ($h=opendir($path)) == false)
		{
			return array('files' => array(), 'dirs' => array());
		}

		$this->size = 0;
		while(false!==($f=readdir($h)))
		{
			// exclude all ignored files/folders and folder starts with dot '.'
			if(in_array($f,$ignore) || substr($f,0,1) == '.')
			{
				continue;
			}
			
			if(is_dir($path . $f))
			{
				$dirs[strtolower(preg_replace('/[.,_!-\s]/','', $f))] = array(
							'name'	=> $f,
							'date'	=> filemtime($path . $f),
							'url'	=> Config::get('baseurl') . (Config::get('mask_url') ? 'dir/' : 'index.php?dir=') . Encrypt::encode(trim($openDir . "/" . $f, "/"), $key),
							'path'	=> $openDir
						);
				
				if($deep == true) {
					$folder	= $this->openDir($openDir . DS . $f);
					$dirs	+= $folder['dirs'];
					$files	+= $folder['files'];
				}
			}
			else
			{
				$size		= filesize($path . $f);
				$url		= trim($openDir . DS . rawurlencode($f), DS);
				$encrypted	= (Config::get('mask_url') ? "get/" : "get.php?file=" ) . Encrypt::encode($url, $key);
				$files[strtolower(preg_replace('/[.,_!-\s]/','', $f))] = array(
																'name'	=> $f,
																'size'	=> $size,
																'date'	=> filemtime($path . $f),
																'url'	=> Config::get('baseurl') . (Config::get('mask_url') ? $encrypted : $url),
																'icon'	=> $this->getIcon($f),
																'path'	=> $openDir
															);
				$this->size += $size;
			}
		}
		
		if($search) {
			$searchFunc = function($file) {
								$search	= isset($_GET['search']) ? strtolower($_GET['search']) : false;
								if(strpos(strtolower($file['name']), $search) === false) {
									return false;
								}
								return true;
							};
			
			$files = array_filter($files, $searchFunc);
			$dirs = array_filter($dirs, $searchFunc);
		}
			
		$this->count = count($files) + count($dirs);
		
		
		ksort($dirs);
		ksort($files);
		closedir($h);
		
		return array('dirs' => $dirs, 'files' => $files);
	}
	
	/*
	 * Get files array
	 * 
	 * @access  public
	 * @return  Array
	 */
	public function getFiles() {
		return $this->files;
	}
	
	/*
	 * Get directories array
	 * 
	 * @access  public
	 * @return  Array
	 */
	public function getDirs() {
		return $this->dirs;
	}
	
	/*
	 * Get URL of upper directory
	 * 
	 * @access  public
	 * @return  String
	 */
	public function getUpURL() {
		$url = Config::get('baseurl') . (Config::get('mask_url') ? 'dir/' : 'index.php?dir=') . Encrypt::encode(trim($this->up_dir), Config::get('key'));
		$url = ($this->up_dir != '' && $this->up_dir != '.') ? $url : Config::get('baseurl');
		
		return $url;
	}
	
	/*
	 * Get icon for file
	 * 
	 * @access  public
	 * @param  $string  Filename
	 * @return  String  Icon class according to FontAwesome, without icon- prefix
	 */
	private function getIcon($f) {
		$icons = Config::get('icons');
		$ext = substr($f, strrpos($f, '.')+1, 3);
		
		if(isset($icons[$ext]))
		{
			return $icons[$ext];
		}
		
		return 'file';
	}
	
	/*
	 * Get folder trails
	 * 
	 * @access  public
	 * @return  Array
	 */
	public function getTrails() {
		$trails = explode("/", $this->current);
		
		return $trails;
	}


	/*
	 * Format size from bytes to human-readable format
	 * 
	 * @static
	 * @access  public
	 * @param  Int  $size  Size to be formatted
	 * @return  String  Formatted size
	 */
	public static function format($size) 
	{
		$type = array('B','KB','MB','GB','TB');
		$count = 0;
		while($size > 1024) 
		{
			$count++;
			$size /= 1024;
		}

		return number_format($size, 2) . $type[$count];
	}
}
?>