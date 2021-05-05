<?php
namespace PHPXI\Libraries\Hooking;

class Hook{
	private static $hook;

	public static function add_action($where, $function, $primacy)
	{
		if(!isset(self::$hook[$where]))
			self::$hook[$where] = array();

			self::$hook[$where][$function] = $primacy;
	}

	public static function remove_action($where, $function)
	{
		if(isset(self::$hook[$where][$function]))
			unset(self::$hook[$where][$function]);
	}

	public static function action_work($where, $args)
	{
		if(isset(self::$hook[$where])){
			$dizi = self::$hook[$where];
			arsort($dizi);
			foreach($dizi as $function=>$primacy){
				call_user_func_array($function, $args);
			}
		}
	}

}
