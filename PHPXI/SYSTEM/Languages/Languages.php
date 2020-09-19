<?php
namespace PHPXI\SYSTEM;

class Languages{
	public $lang;

	function __construct(){
		$this->load();
	}

	function load($lang = ""){
		if($lang == ""){
			global $config;
			$lang = $config["language"];
		}
		
		$path = PHPXI . "APPLICATION/Languages/" . $lang . "/app.php";
		$lang = array();
		require_once($path);
		$this->lang = $lang;
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
		echo $this->lang[$key];
	}

}