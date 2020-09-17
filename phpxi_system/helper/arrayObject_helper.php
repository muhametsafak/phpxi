<?php
if(!defined("PHPXI")){
  die("You cannot access this page.");
}

function arrayObject($array) {
  $object = new stdClass();
  foreach ($array as $key => $value) {
      if (is_array($value)) {
          $value = arrayObject($value);
      }
      $object->$key = $value;
  }
  return $object;
}


function objectArray($object){
  $array = array();
  $class = get_class($object);
  $methods = get_class_methods($class);
  foreach ($methods as $method) {
      preg_match(' /^(get)(.*?)$/i', $method, $results);
      $pre = $results[1]  ?? '';
      $k = $results[2]  ?? '';
      $k = strtolower(substr($k, 0, 1)) . substr($k, 1);
      If ($pre == 'get') {
          $array[$k] = $object->$method();
      }
  }
  return $array;
}
