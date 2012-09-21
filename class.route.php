<?php
class Route {
	
	/*
	 * initialized routes
	 * 
	 * @access  private
	 */
	private static $segments;


	/*
	 * Init routing
	 * 
	 * @static
	 * @access  public
	 * @return  void
	 */
	public static function init() {
		$exploded = array_values(array_filter(explode('/', $_SERVER['REQUEST_URI'])));
		
		$key = 1; // start
		if(Config::get('rewrite') == false) {
			$key++;
		}

		if(empty($exploded)) {
			return;
		}
		
		if($exploded[$key-1] == 'get') {
			File::send($exploded[$key]);
		}
		
		$segments['dir']	= isset($exploded[$key]) ? $exploded[$key++] : null;
		$segments['search'] = isset($exploded[$key]) ? $exploded[$key++] : null;
		$segments['deep']	= isset($exploded[$key]) ? $exploded[$key++] : null;
		
		static::$segments = $segments;
	}
	
	/*
	 * get a specific portion of url segment
	 * 
	 * @static
	 * @access  public
	 * @param   Int  Route segment. Optional.
	 * @return  Mixed  Return all routes or false if non found
	 */
	public static function get($segment = null) {
		if(!is_null($segment) && static::$segments[$segment]) {
			return static::$segments[$segment];
		}
		
		return false;
	}
	
}

Route::init();