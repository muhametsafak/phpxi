<?php
namespace PHPXI\Libraries\Language;

use \PHPXI\Libraries\Config\Config as Config;
use \PHPXI\Libraries\Debugging\Logger as Logger;

class Language{
	private static $lang;
	private static $set;

	public static function autoload(){
		if(Config::get("language.multi")){
			$request_uri = trim(mb_strtolower(mb_substr($_SERVER["PHP_SELF"], strlen($_SERVER["SCRIPT_NAME"]), strlen($_SERVER["PHP_SELF"]), "UTF-8"), "UTF-8"), "/");
			$parse = explode("/", $request_uri);
			if(isset($parse[0]) and trim($parse[0]) != ""){
				self::set($parse[0]);
			}else{
				self::set(Config::get("language.default"));
			}
		}else{
			self::set(Config::get("language.default"));
		}
	}

	public static function set($set){
		self::$set = $set;
		self::load();
	}

	public static function get(){
		return self::$set;
	}

	public static function load(){
		$path = APPLICATION_PATH . "Languages/" . self::$set . "/app.php";
		if(file_exists($path) || DEVELOPMENT){
			$lang = array();
			require_once($path);
			self::$lang = $lang;
		}else{
			self::set(Config::get("language.default"));
			Logger::system("Default (" . self::$set . ") language file loaded because defined language file could not be found");
		}
	}
	
	public static function r($key, $value = []){
		if(isset(self::$lang[$key])){
		  $return = self::$lang[$key];
		}else{
		  $return = $key;
		}
		if(sizeof($value) > 0){
		  for ($i=0; $i < sizeof($value); $i++) {
			$return = str_replace("{".$i."}", $value[$i], $return);
		  }
		}
		return $return;
	}
	
	public static function e($key, $value = []){
		echo self::r($key, $value);
	}

}