<?php
if(!defined("PHPXI")){
  die("You cannot access this page.");
}

class PHPXI_PLUGIN{
	private $hook;

	function add_action($where, $function, $primacy){
		if(!isset($this->hook[$where]))
			$this->hook[$where] = array();

			$this->hook[$where][$function] = $primacy;
	}

	function remove_action($where, $function){
		if(isset($this->hook[$where][$function]))
			unset($this->hook[$where][$function]);
	}

	function action_work($where, $args){
		if(isset($this->hook[$where])){
			$dizi = $this->hook[$where];
			arsort($dizi);
			foreach($dizi as $function=>$primacy){
				call_user_func_array($function, $args);
			}
		}
	}

}
