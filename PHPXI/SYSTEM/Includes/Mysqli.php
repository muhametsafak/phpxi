<?php
namespace PHPXI;

class Mysqli{

  private string $host;
  private string $user;
  private string $password;
  private string $name;
  private string $prefix;
  private string $charset;
  
  public $mysqli;
	protected $query;
	
  private string $error;
	private int $query_size = 0;
  private int $insert_id;
  private int $num_rows;
  private array $select = [];
  private array $from = [];
  private string $selected_from;
  private array $where = [];
  private array $and_where = [];
  private array $or_where = [];
  private array $having = [];
  private array $group_by = [];
  private string $limit;
  private array $order_by = [];
  private $get;
  private $rows;

  private bool $cache_status = false;
  private int $cache_timeout = 3600;
  


	function __construct($host = 'localhost', $user = 'root', $password = '', $name = '', $charset = 'utf8', $prefix = '') {
	  $this->host = $host;
	  $this->user = $user;
	  $this->password = $password;
    $this->name = $name;
    $this->setPrefix($prefix);
    $this->setCharset($charset);
	  $this->connect();
	}
	
	function __destruct(){
	  $this->disconnect();
	}

  function connect(): object{
		$this->mysqli = new \mysqli($this->host, $this->user, $this->password, $this->name);
		if ($this->mysqli->connect_errno) {
      error_log("MySQLI DB Connect ERROR : " . $this->mysqli->connect_errno . " : " . $this->mysqli->connect_error , 0);
      die("MySQLI Connect ERROR : ".$this->mysqli->connect_errno . ":". $this->mysqli->connect_error);
		}
		$this->mysqli->set_charset($this->charset);
    $this->mysqli->query("SET NAMES ".$this->charset);
    return $this;
  }
  
  function disconnect(): object{
    $this->mysqli->close();
    return $this;
  }

  public function setPrefix(string $prefix): void{
    $this->prefix = $prefix;
  }

  public function setCharset(string $charset): void{
    $this->charset = $charset;
  }

  public function cache(bool $status = true, int $timeout = 3600): object{
    $this->cache_status = $status;
    $this->cache_timeout = $timeout;
    return $this;
  }

  public function select(string $select = "*"): object{
    if($select != "*"){
      $selects = explode(",", $select);
      foreach ($selects as $row) {
        $as_rows = explode(" as ", strtolower($row));
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
    return $this;
  }

  public function from(string $from): object{
    $this->from[] = $from;
    $this->selected_from($from);
    return $this;
  }

  public function selected_from(string $from): object{
    $this->selected_from = $from;
    return $this;
  }

  public function join(string $from): object{
    $this->from($from);
    return $this;
  }

  public function join_where(string $from, string $from_column, string $join_from, string $join_column): object{
    $this->where[] = "`".$this->prefix.$from."`.`".$from_column."`=`".$this->prefix.$join_from."`.`".$join_column."`";
    return $this;
  }

  public function where(string $column, string $value, string $operator = "="): object{
    switch (strtolower($operator)) {
      case '=':
        $this->where[] = "`".$this->prefix.$this->selected_from."`.`".$column."`='".$value."'";
        break;
      case 'in':
        if(is_array($value)){
          $value = implode(", ", $value);
        }
        $this->where[] = "`".$this->prefix.$this->selected_from."`.`".$column."` IN (".$value.")";
        break;
      case 'not in':
        if(is_array($value)){
          $value = implode(", ", $value);
        }
        $this->where[] = "`".$this->prefix.$this->selected_from."`.`".$column."` NOT IN (".$value.")";
        break;
      case 'like':
        $this->where[] = "`".$this->prefix.$this->selected_from."`.`".$column."` LIKE '".$this->escape_string($value)."'";
        break;
      case 'not like':
        $this->where[] = "`".$this->prefix.$this->selected_from."`.`".$column."` NOT LIKE '".$this->escape_string($value)."'";
        break;
      case 'between' : 
        $this->where[] = "`".$this->prefix.$this->selected_from."`.`".$column."` BETWEEN ".$value[0]." AND ".$value[1];
        break;
      case 'not between' : 
        $this->where[] = "`".$this->prefix.$this->selected_from."`.`".$column."` NOT BETWEEN ".$value[0]." AND ".$value[1];
        break;
      default:
        $this->where[] = "`".$this->prefix.$this->selected_from."`.`".$column."`".$operator."'".$value."'";
        break;
    }
    return $this;
  }

  public function and_where(string $column, string $value, string $operator = "="): object{
    switch (strtolower($operator)) {
      case '=':
        $this->and_where[] = "`".$this->prefix.$this->selected_from."`.`".$column."`='".$value."'";
        break;
      case 'in':
        if(is_array($value)){
          $value = implode(", ", $value);
        }
        $this->and_where[] = "`".$this->prefix.$this->selected_from."`.`".$column."` IN (".$value.")";
        break;
      case 'not in':
        if(is_array($value)){
          $value = implode(", ", $value);
        }
        $this->and_where[] = "`".$this->prefix.$this->selected_from."`.`".$column."` NOT IN (".$value.")";
        break;
      case 'like':
        $this->and_where[] = "`".$this->prefix.$this->selected_from."`.`".$column."` LIKE '".$this->escape_string($value)."'";
        break;
      case 'not like':
        $this->and_where[] = "`".$this->prefix.$this->selected_from."`.`".$column."` NOT LIKE '".$this->escape_string($value)."'";
        break;
      case 'between' : 
        $this->and_where[] = "`".$this->prefix.$this->selected_from."`.`".$column."` BETWEEN ".$value[0]." AND ".$value[1];
        break;
      case 'not between' : 
        $this->and_where[] = "`".$this->prefix.$this->selected_from."`.`".$column."` NOT BETWEEN ".$value[0]." AND ".$value[1];
        break;
      default:
        $this->and_where[] = "`".$this->prefix.$this->selected_from."`.`".$column."`".$operator."'".$value."'";
        break;
    }
    return $this;
  }

  public function or_where(string $column, string $value, string $operator = "="): object{
    switch (strtolower($operator)) {
      case '=':
        $this->or_where[] = "`".$this->prefix.$this->selected_from."`.`".$column."`='".$value."'";
        break;
      case 'in':
        if(is_array($value)){
          $value = implode(", ", $value);
        }
        $this->or_where[] = "`".$this->prefix.$this->selected_from."`.`".$column."` IN (".$value.")";
        break;
      case 'not in':
        if(is_array($value)){
          $value = implode(", ", $value);
        }
        $this->or_where[] = "`".$this->prefix.$this->selected_from."`.`".$column."` NOT IN (".$value.")";
        break;
      case 'like':
        $this->or_where[] = "`".$this->prefix.$this->selected_from."`.`".$column."` LIKE '".$this->escape_string($value)."'";
        break;
      case 'not like':
        $this->or_where[] = "`".$this->prefix.$this->selected_from."`.`".$column."` NOT LIKE '".$this->escape_string($value)."'";
        break;
      case 'between' : 
        $this->or_where[] = "`".$this->prefix.$this->selected_from."`.`".$column."` BETWEEN ".$value[0]." AND ".$value[1];
        break;
      case 'not between' : 
        $this->or_where[] = "`".$this->prefix.$this->selected_from."`.`".$column."` NOT BETWEEN ".$value[0]." AND ".$value[1];
        break;
      default:
        $this->or_where[] = "`".$this->prefix.$this->selected_from."`.`".$column."`".$operator."'".$value."'";
        break;
    }
    return $this;
  }

  
  public function having(string $column, string $value, string $operator = "="): object{
    switch (strtolower($operator)) {
      case '=':
        $this->having[] = "`".$this->prefix.$this->selected_from."`.`".$column."`='".$value."'";
        break;
      case 'in':
        if(is_array($value)){
          $value = implode(", ", $value);
        }
        $this->having[] = "`".$this->prefix.$this->selected_from."`.`".$column."` IN (".$value.")";
        break;
      case 'not in':
        if(is_array($value)){
          $value = implode(", ", $value);
        }
        $this->having[] = "`".$this->prefix.$this->selected_from."`.`".$column."` NOT IN (".$value.")";
        break;
      case 'like':
        $this->having[] = "`".$this->prefix.$this->selected_from."`.`".$column."` LIKE '".$this->escape_string($value)."'";
        break;
      case 'not like':
        $this->having[] = "`".$this->prefix.$this->selected_from."`.`".$column."` NOT LIKE '".$this->escape_string($value)."'";
        break;
      case 'between' : 
        $this->having[] = "`".$this->prefix.$this->selected_from."`.`".$column."` BETWEEN ".$value[0]." AND ".$value[1];
        break;
      case 'not between' : 
        $this->having[] = "`".$this->prefix.$this->selected_from."`.`".$column."` NOT BETWEEN ".$value[0]." AND ".$value[1];
        break;
      default:
        $this->having[] = "`".$this->prefix.$this->selected_from."`.`".$column."`".$operator."'".$value."'";
        break;
    }
    return $this;
  }

  public function group_by(string $group = ""): void{
    $this->group_by[] = $group;
  }

  public function limit(string $limit): object{
    $this->limit = $limit;
    return $this;
  }

  public function order_by(string $by = "", string $order = "", string $from = ""): object{
    if($from == ""){
      $from = $this->selected_from;
    }
    if($order == ""){
      $this->order_by[] = $by;
    }else{
      if(strtolower($order) == "asc"){
        $order = "ASC";
      }else{
        $order = "DESC";
      }
      $this->order_by[] = "`".$this->prefix.$from."`.`".$by."` ".$order;
    }
    return $this;
  }


  public function query_where_create(): string{
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

  public function sql(): string{
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

    if(sizeof($this->group_by) > 0){
      $sqls['GROUP BY'] = implode(", ", $this->group_by);
    }

    if(sizeof($this->having) > 0){
      $sqls['HAVING'] = implode(" AND ", $this->having);
    }

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


  public function get(string $from = ""){
    if($from != ""){
      $this->from($from);
    }
    return $this->get = $this->query($this->sql());
  }

  public function rows($query = ""){
    if($query != ""){
      return $query->fetch_assoc();
    }else{
      if($this->cache_status){
        return $this->get;
      }else{
        return $this->get->fetch_assoc();
      }
    }
  }
  
  public function results($query = ""){
    if($query != ""){
      return $query->fetch_object();
    }else{
      if($this->cache_status){
        return arrayObject($this->get);
      }else{
        return $this->get->fetch_object();
      }
    }
  }
  
  public function num_rows($query = ""): int{
    if($query != ""){
      return $query->num_rows;
    }else{
      return $this->num_rows;
    }
  }

  public function insert(array $data = []): bool{
    if(sizeof($data) > 0){
      $keys = array();
      $values = array();
      foreach ($data as $key => $value) {
        $keys[] = "`".$key."`";
        $values[] = "'".$this->escape_string($value)."'";
      }

      $sql = "INSERT INTO `".$this->prefix.$this->selected_from."`(".implode(", ", $keys).") VALUES (".implode(", ", $values).")";
      $this->cache(false);
      if($this->query($sql)){
        return true;
      }else{
        return false;
      }
    }
  }

  public function delete(array $where = []): bool{
    if(sizeof($where) > 0){
      $rows = array();
      foreach ($where as $key => $value) {
        $rows[] = "`".$key."`='".$this->escape_string($value)."'";
      }
      $sql = "DELETE FROM `".$this->prefix.$this->selected_from."` WHERE ".implode(" AND ", $rows);
    }else{
      $sql = "DELETE FROM `".$this->prefix.$this->selected_from."` WHERE ".$this->query_where_create();
    }
    $this->cache(false);
    if($this->query($sql)){
      return true;
    }else{
      return false;
    }
  }

  public function update(array $data = []): bool{
    $rows = array();
    foreach ($data as $key => $value) {
      $rows[] = "`".$key."`='".$this->escape_string($value)."'";
    }
    $sql = "UPDATE `".$this->prefix.$this->selected_from."` SET ".implode(", ", $rows)." WHERE ".$this->query_where_create();
    $this->cache(false);
    if($this->query($sql)){
      return true;
    }else{
      return false;
    }
  }
  
	public function count(): int{
    $this->query_size++;
	  $sql = $this->mysqli->query($this->sql());
	  return $sql->num_rows;
  }
  
  public function drop(string $table = ""): bool{
    $this->query_size++;
    if($table == ""){
      $table = $this->selected_from;
    }
    if($this->mysqli->query("DROP TABLE `".$table."`")){
      return true;
    }else{
      return false;
    }
  }

  public function truncate(string $table = ""): bool{
    $this->query_size++;
    if($table == ""){
      $table = $this->selected_from;
    }
    if($this->mysqli->query("TRUNCATE `".$table."`")){
      return true;
    }else{
      return false;
    }
  }

	public function query(string $sql){
    if($this->cache_status){
      $path = SQL_CACHE_PATH.md5($sql).".json";
      if(file_exists($path) and (filemtime($path) > (time() - $this->cache_timeout))){
        $cache_content = file_get_contents($path);
        $result = json_decode($cache_content, true);
        $this->num_rows = $result["num_rows"];
        $return = $result["results"];
      }else{
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
        $results = array();
        while($row = $res->fetch_assoc()){
          $results[] = $row;
        }
        $result = array(
          "num_rows" => $this->num_rows,
          "results" => $results
        );
        $return = $results;
        unset($results);
        $cache_content = json_encode($result);
        file_put_contents($path, $cache_content);
      }
    }else{
      $return = $this->mysqli->query($sql) or error_log("SQL QUERY ERROR : " . $this->mysqli->error . " QUERY : " . $sql, 0);
      if(isset($this->mysqli->insert_id)){
        $this->insert_id = $this->mysqli->insert_id;
      }else{
        $this->insert_id = 0;
      }
      if(isset($return->num_rows)){
        $this->num_rows = $return->num_rows;
      }else{
        $this->num_rows = 0;
      }
    }
    $this->clear();
    return $return;
	}
	
	public function clear(): void{
    $this->select = array();
    $this->from = array();
    $this->selected_from = null;
    $this->where = array();
    $this->and_where = array();
    $this->or_where = array();
    $this->having = array();
    $this->group_by = array();
    $this->limit = "";
    $this->order_by = array();
	}
	
	public function insert_id(): int{
	  return $this->insert_id;
	}
	
  public function escape_string(string $string): string{
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

  public function query_size(): int{
    return $this->query_size;
  }
  
}
