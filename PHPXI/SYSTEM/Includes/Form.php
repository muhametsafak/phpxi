<?php
namespace PHPXI;

class Form{
  private string $return;
	
	function start(array $form = []): void{
    $this->return = '<form';
    foreach ($form as $key => $value) {
      $this->return .= ' '.$key.'="'.$value.'"';
    }
    $this->return .= '>';
	}

  function open_div(array $div = []): void{
    $this->return .= ' <div';
    foreach ($div as $key => $value) {
      $this->return .= ' '.$key.'="'.$value.'" ';
    }
    $this->return .= '> ';
  }

  function close_div(): void{
    $this->return .= ' </div> ';
  }

  function add_label(string $key = "", string $value = ""): void{
    $return = '<label for="'.$key.'">'.$value.'</label>';
    $this->return .= $return;
  }

  function add_input($input = ""): void{
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

  function add_select(array $select = [], array $options = [], string $selected_id = NULL): void{
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

  function add_textarea(array $textarea = [], string $textarea_value = ""): void{
    $return = ' <textarea ';
    foreach ($textarea as $key => $value) {
      $return .= $key.'="'.$value.'" ';
    }
    $return .= '>'.$textarea_value.'</textarea>';
    $this->return .= $return;
  }

  function add_button(array $button = [], string $text = ""): void{
    $return = '<button';
    foreach ($button as $key => $value) {
      $return .= ' '.$key.'="'.$value.'"';
    }
    $return .= '>'.$text.'</button>';
    $this->return .= $return;
  }

  function add_option(string $key, string $value, string $selected_id = ""): void{
    if($selected_id == $key){
      $return = '<option value="'.$key.'" selected>'.$value.'</option>';
    }else{
      $return = '<option value="'.$key.'">'.$value.'</option>';
    }
    $this->return .= $return;
  }

  function add_html(string $html = ""): void{
    $this->return .= $html;
  }

  function return(): string{
    $this->return .= '</form>';
    $return = $this->return;
    $this->return = null;
    return $return;
  }
}
