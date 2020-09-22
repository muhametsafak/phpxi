<?php
namespace PHPXI\SYSTEM;

class Languages{
	private $lang;
	private $set;

	function __construct(){
		global $config;
		$this->set($config["language"]);
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
			die("ERROR : Language file not found. " . $path . "<br />\n");
		}
	}
	
	function r($key, $value = array()){
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
	
	function e($key, $value = array()){
		echo $this->r($key, $value);
	}

}