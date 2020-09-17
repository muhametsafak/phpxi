<?php
if(!defined("PHPXI")){
  die("You cannot access this page.");
}

class PHPXI_MySQLi{

  private $host;
  private $user;
  private $password;
  private $name;
  private $prefix;
  private $charset;
  
  protected $mysqli;
	protected $query;
	
  public $error;
	public $query_size = 0;
  public $insert_id;
  public $num_rows;
  public $select = array();
  public $from = array();
  public $selected_from;
  public $where = array();
  public $and_where = array();
  public $or_where = array();
  public $limit;
  public $order_by = array();
  private $get;
  private $rows;



	function __construct($host = 'localhost', $user = 'root', $password = '', $name = '', $charset = 'utf8', $prefix = '') {
	  $this->host = $host;
	  $this->user = $user;
	  $this->password = $password;
	  $this->name = $name;
	  $this->prefix = $prefix;
	  $this->charset = $charset;
	  $this->connect();
	}
	
	function __destruct(){
	  $this->disconnect();
	}

  function connect(){
		$this->mysqli = new mysqli($this->host, $this->user, $this->password, $this->name);
		if ($this->mysqli->connect_errno) {
      error_log("MySQLI DB Connect ERROR : " . $this->mysqli->connect_errno . " : " . $this->mysqli->connect_error , 0);
      die("MySQLI Connect ERROR : ".$this->mysqli->connect_errno . ":". $this->mysqli->connect_error);
		}
		$this->mysqli->set_charset($this->charset);
    $this->mysqli->query("SET NAMES ".$this->charset);
  }
  
  function disconnect(){
    return $this->mysqli->close();
  }


  public function select($select = "*"){
    if($select != "*"){
      $selects = explode(",", $select);
      foreach ($selects as $row) {
        $as_rows = explode("as", strtolower($row));
        if(sizeof($as_rows) > 1){
          $as = $as_rows[1];
        }
        $rows = explode(".", $as_rows[0]);
        if(sizeof($rows) > 1){
          if(trim($rows[1]) == "*"){
            $this->select[] = "`".trim($rows[0])."`.*";
          }else{
            if(isset($as) and $as != ""){
              $this->select[] = "`".trim($rows[0])."`.`".trim($rows[1])."` AS `".$as."`";
            }else{
              $this->select[] = "`".trim($rows[0])."`.`".trim($rows[1])."`";
            }
          }
        }else{
          if(isset($as) and $as != ""){
            $this->select[] = "`".trim($rows[0])."` AS `".$as."`";
          }else{
            $this->select[] = "`".trim($rows[0])."`";
          }
        }
        $as = null;
      }
    }else{
      $this->select = "*";
    }
  }

  public function from($from){
    $this->from[] = $from;
    $this->selected_from($from);
  }

  public function selected_from($from){
    $this->selected_from = $from;
  }

  public function join($from){
    $this->from($from);
  }

  public function join_where($from, $from_column, $join_from, $join_column){
    $this->where[] = "`".$this->prefix.$from."`.`".$from_column."`=`".$this->prefix.$join_from."`.`".$join_column."`";
  }

  public function where($column, $value, $operator = "="){
    $this->where[] = "`".$this->prefix.$this->selected_from."`.`".$column."`".$operator."'".$value."'";
  }

  public function and_where($column, $value, $operator = "="){
    $this->and_where[] = "`".$this->prefix.$this->selected_from."`.`".$column."`".$operator."'".$value."'";
  }

  public function or_where($column, $value, $operator = "="){
    $this->or_where[] = "`".$this->prefix.$this->selected_from."`.`".$column."`".$operator."'".$value."'";
  }
  
  public function in_where($column, $value, $andor = "and"){
    if(is_array($value)){
      $value = implode(", ", $value);
    }
    if(strtolower($andor) == "or"){
      $this->or_where[] = "`".$this->prefix.$this->selected_from."`.`".$column."` IN (".$value.")";
    }else{
      $this->and_where[] = "`".$this->prefix.$this->selected_from."`.`".$column."` IN (".$value.")";
    }
  }
  
  public function not_in_where($column, $value, $andor = "and"){
    if(is_array($value)){
      $value = implode(", ", $value);
    }
    if(strtolower($andor) == "or"){
      $this->or_where[] = "`".$this->prefix.$this->selected_from."`.`".$column."` NOT IN (".$value.")";
    }else{
      $this->and_where[] = "`".$this->prefix.$this->selected_from."`.`".$column."` NOT IN (".$value.")";
    }
  }
  
  public function like_where($column, $value, $andor = "and"){
    if(strtolower($andor) == "or"){
      $this->or_where[] = "`".$this->prefix.$this->selected_from."`.`".$column."` LIKE (".$db->escape_string($value).")";
    }else{
      $this->and_where[] = "`".$this->prefix.$this->selected_from."`.`".$column."` LIKE (".$db->escape_string($value).")";
    }
  }
  
  public function not_like_where($column, $value, $andor = "and"){
    if(strtolower($andor) == "or"){
      $this->or_where[] = "`".$this->prefix.$this->selected_from."`.`".$column."` NOT LIKE '".$db->escape_string($value)."'";
    }else{
      $this->and_where[] = "`".$this->prefix.$this->selected_from."`.`".$column."` NOT LIKE '".$db->escape_string($value)."'";
    }
  }

  public function limit($limit){
    $this->limit = $limit;
  }

  public function order_by($by = "", $order = "asc", $from = ""){
    if($from == ""){
      $from = $this->selected_from;
    }
    if(strtolower($order) == "asc"){
      $order = "ASC";
    }else{
      $order = "DESC";
    }
    $this->order_by[] = "`".$this->prefix.$from."`.`".$by."` ".$order;
  }


  public function query_where_create(){
    $wheres = array();
    if(is_array($this->where) and sizeof($this->where) > 0){
      $wheres[] = implode(" AND ", $this->where);
    }
    if(is_array($this->and_where) and sizeof($this->and_where) > 0){
      $wheres[] = implode(" AND ", $this->and_where);
    }
    if(is_array($this->or_where) and sizeof($this->or_where) > 0){
      $wheres[] = implode(" OR ", $this->or_where);
    }
    if(sizeof($wheres) > 0){
      $where = implode(" AND ", $wheres);
    }else{
      $where = "1";
    }
    return $where;
  }

  public function sql(){
    $sqls = array();
    if(is_array($this->select) and sizeof($this->select) > 0){
      $sqls['SELECT'] = implode(", ", $this->select);
    }else {
      $sqls['SELECT'] = $this->select;
    }

    $froms = array();
    foreach ($this->from as $row) {
      $froms[] = "`".$this->prefix.$row."`";
    }
    $sqls['FROM'] = implode(", ", $froms);

    $sqls['WHERE'] = $this->query_where_create();

    if(is_array($this->order_by) and sizeof($this->order_by) > 0){
      $sqls['ORDER BY'] = implode(", ", $this->order_by);
    }

    if(trim($this->limit) != ""){
      $sqls['LIMIT'] = $this->limit;
    }

    $sql = "";
    foreach ($sqls as $key => $value) {
      $sql .= " ".$key." ".$value;
    }
    return $sql;
  }



  public function get(){
    return $this->get = $this->query($this->sql());
  }

  public function rows($query = ""){
    if($query != ""){
      return $query->fetch_assoc();
    }else{
      return $this->get->fetch_assoc();
    }
  }
  
  public function results($query = ""){
    if($query != ""){
      return $query->fetch_object();
    }else{
      return $this->get->fetch_object();
    }
  }
  
  public function num_rows($query = ""){
    if($query != ""){
      return $query->num_rows;
    }else{
      return $this->num_rows;
    }
  }


  public function insert($data = array()){
    if(sizeof($data) > 0){
      $keys = array();
      $values = array();
      foreach ($data as $key => $value) {
        $keys[] = "`".$key."`";
        $values[] = "'".$this->escape_string($value)."'";
      }

      $sql = "INSERT INTO `".$this->prefix.$this->selected_from."`(".implode(", ", $keys).") VALUES (".implode(", ", $values).")";
      if($this->query($sql)){
        return true;
      }else{
        return false;
      }
    }
  }

  public function delete($where = array()){
    if(sizeof($where) > 0){
      $rows = array();
      foreach ($where as $key => $value) {
        $rows[] = "`".$key."`='".$this->escape_string($value)."'";
      }
      $sql = "DELETE FROM `".$this->prefix.$this->selected_from."` WHERE ".implode(" AND ", $rows);
    }else{
      $sql = "DELETE FROM `".$this->prefix.$this->selected_from."` WHERE ".$this->query_where_create();
    }
    if($this->query($sql)){
      return true;
    }else{
      return false;
    }
  }

  public function update($data = array()){
    $rows = array();
    foreach ($data as $key => $value) {
      $rows[] = "`".$key."`='".$this->escape_string($value)."'";
    }
    $sql = "UPDATE `".$this->prefix.$this->selected_from."` SET ".implode(", ", $rows)." WHERE ".$this->query_where_create();
    if($this->query($sql)){
      return true;
    }else{
      return false;
    }
  }
  
	public function count(){
    $this->query_size++;
	  $sql = $this->mysqli->query($this->sql());
	  return $sql->num_rows;
	}

	public function query($sql){
		$this->query_size = $this->query_size + 1;
		$res = $this->mysqli->query($sql) or error_log("SQL QUERY ERROR : " . $this->mysqli->error . " QUERY : " . $sql, 0);
    if(isset($this->mysqli->insert_id)){
      $this->insert_id = $this->mysqli->insert_id;
    }else{
      $this->insert_id = 0;
    }
    if(isset($res->num_rows)){
      $this->num_rows = $res->num_rows;
    }else{
      $this->num_rows = 0;
    }
    $this->clear();
    return $res;
	}
	
	public function clear(){
    $this->select = array();
    $this->from = array();
    $this->selected_from = null;
    $this->where = array();
    $this->and_where = array();
    $this->or_where = array();
    $this->limit = "";
    $this->order_by = array();
	}
	
	public function insert_id(){
	  return $this->insert_id;
	}
	
  public function escape_string($string){
    $string = $this->mysqli->escape_string($string);
    return $string;
  }
  
  public function return(){
    $size = $this->num_rows();
    if($size > 0){
      $return = array();
      while($row = $this->rows()){
        $return[] = $row;
      }
      return $return;
    }else{
      return false;
    }
  }
  
}
