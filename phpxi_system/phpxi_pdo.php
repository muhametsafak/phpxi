<?php
if(!defined("PHPXI")){
  die("You cannot access this page.");
}

class PHPXI_PDO{

    public $host;
    public $user;
    public $password;
    public $name;
    public $prefix;
    public $charset;
    
    public $pdo;
    
    function __construct($host, $user, $password, $name, $charset = "utf8", $prefix = ""){
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
        $this->name = $name;
        $this->prefix = $prefix;
        $this->charset = $charset;
        $this->connect();
    }
    
    
    function connect(){
        try {
            $this->pdo = new PDO("mysql:host=".$this->host.";dbname=".$this->name.";charset=".$this->charset, $this->user, $this->password);
        } catch ( PDOException $e ){
            echo $e->getMessage();
        }
    }

    function disconnect(){
        $this->pdo = null;
    }

    public function query($sql){
        return $this->pdo->query($sql);
    }



}