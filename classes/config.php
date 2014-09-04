<?php
class Config{
	public static function get($path = null){
		if($path){
			$config = $GLOBALS['config'];
			$path = explode('/', $path);

			foreach($path as $bit){
				if(isset($config[$bit])){
					$config = $config[$bit];
				}
			}
			// setting the config to chosen bit
			// does mysql exist inside config, if so config = mysql
			return $config;
		}

		return false;
	}
}
?>