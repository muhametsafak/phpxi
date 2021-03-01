<?php
namespace PHPXI;

class Language{
	private $lang;
	private $set;

	function __construct(){
		global $config;
		if(MULTI_LANGUAGES){
			$request_uri = trim(mb_strtolower(mb_substr($_SERVER["PHP_SELF"], strlen($_SERVER["SCRIPT_NAME"]), strlen($_SERVER["PHP_SELF"]), "UTF-8"), "UTF-8"), "/");
			$parse = explode("/", $request_uri);
			if(isset($parse[0]) and trim($parse[0]) != ""){
				$this->set($parse[0]);
			}else{
				$this->set(DEFAULT_LANGUAGE);
			}
		}else{
			$this->set(DEFAULT_LANGUAGE);
		}
	}

	public function set($set){
		$this->set = $set;
		$this->load();
	}

	public function get(){
		return $this->set;
	}

	public function load(){
		$path = PHPXI . "APPLICATION/Languages/" . $this->set . "/app.php";
		if(file_exists($path)){
			$lang = array();
			require_once($path);
			$this->lang = $lang;
		}else{
			$this->set(DEFAULT_LANGUAGE);
		}
	}
	
	function r($key, $value = []){
		if(isset($this->lang[$key])){
		  $return = $this->lang[$key];
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
	
	function e($key, $value = []){
		echo $this->r($key, $value);
	}

}