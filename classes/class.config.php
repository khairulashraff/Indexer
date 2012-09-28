<?php
define('DS', DIRECTORY_SEPARATOR);

class Config {
	
	/*
	 * configurations
	 * 
	 * @static
	 * @access  public
	 */
	private static $configs = array();
	
	/*
	 * Constructor. 
	 * 
	 * @static
	 * @access  public
	 * @return  void
	 */
	public static function load($file = null) {
		$path = dirname(dirname(__FILE__)) . '/' . ($file ? $file : 'config.php');
		if(file_exists($path)) {
			static::$configs = include_once($path);
		}
	}
	
	/*
	 * Get a specifc configuration by key
	 * 
	 * @static
	 * @access  public
	 * @param  String  $key  Configuration key
	 * @return  String  Configuration value
	 */
	public static function get($key = null) {
		if(isset(static::$configs[$key])) {
			return static::$configs[$key];
		}
		
		return false;
	}
}

Config::load();

include_once(dirname(__FILE__) . '/class.folder.php');
include_once(dirname(__FILE__) . '/class.encrypt.php');
include_once(dirname(__FILE__) . '/class.file.php');
include_once(dirname(__FILE__) . '/class.route.php');
