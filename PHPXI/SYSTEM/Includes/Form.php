<?php
namespace PHPXI;

class Form{
  private $return;
	
	function start($form = []){
    $this->return = '<form';
    foreach ($form as $key => $value) {
      $this->return .= ' '.$key.'="'.$value.'"';
    }
    $this->return .= '>';
	}

  function open_div($div = []){
    $this->return .= ' <div';
    foreach ($div as $key => $value) {
      $this->return .= ' '.$key.'="'.$value.'" ';
    }
    $this->return .= '> ';
  }

  function close_div(){
    $this->return .= ' </div> ';
  }

  function add_label($key = "", $value = ""){
    $return = '<label for="'.$key.'">'.$value.'</label>';
    $this->return .= $return;
  }

  function add_input($input = ""){
    $return = '<input ';
    if(is_array($input)){
      if(!isset($input['type'])){
        $return .= ' type="text" ';
      }
      foreach ($input as $key => $value) {
        $return .= $key.'="'.$value.'" ';
      }
    }else{
      $return .= ' type="text" ';
    }
    $return .= ' />';
    $this->return .= $return;
  }

  function add_select($select = [], $options = [], $selected_id = NULL){
    $this->return .= '<select ';
    foreach ($select as $key => $value) {
      $this->return .= $key.'="'.$value.'" ';
    }
    $this->return .= '> ';
    foreach ($options as $key => $value) {
      $this->return .= $this->add_option($key, $value, $selected_id);
    }
    $this->return .= ' </select>';
  }

  function add_textarea($textarea = [], $textarea_value = ""){
    $return = ' <textarea ';
    foreach ($textarea as $key => $value) {
      $return .= $key.'="'.$value.'" ';
    }
    $return .= '>'.$textarea_value.'</textarea>';
    $this->return .= $return;
  }

  function add_button($button = [], $text = ""){
    $return = '<button';
    foreach ($button as $key => $value) {
      $return .= ' '.$key.'="'.$value.'"';
    }
    $return .= '>'.$text.'</button>';
    $this->return .= $return;
  }

  function add_option($key, $value, $selected_id = ""){
    if($selected_id == $key){
      $return = '<option value="'.$key.'" selected>'.$value.'</option>';
    }else{
      $return = '<option value="'.$key.'">'.$value.'</option>';
    }
    $this->return .= $return;
  }

  function add_html($html = ""){
    $this->return .= $html;
  }

  function return(){
    $this->return .= '</form>';
    $return = $this->return;
    $this->return = null;
    return $return;
  }
}
