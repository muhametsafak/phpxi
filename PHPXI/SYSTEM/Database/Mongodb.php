<?php
namespace PHPXI\SYSTEM\MONGODB;

class DB{

    private $host;
    private $user;
    private $password;
    private $db_name;
    private $port;
    
    public $mongo;
    
        
    /**
     * __construct
     *
     * @param  mixed $host
     * @param  mixed $user
     * @param  mixed $password
     * @param  mixed $db_name
     * @param  mixed $port
     * @return void
     */
    function __construct($host = "localhost", $user = "", $password = "", $db_name = "", $port = "27017"){
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
        $this->db_name = $db_name;
        $this->port = $port;
        
        if(!$this->is_mongodb()){
            die("ERROR : MongoDB libraries not installed!");
        }
        $this->connect();
    }
        
    /**
     * is_mongodb
     *
     * @return void
     */
    function is_mongodb(){
        if(extension_loaded("MongoDB")){
            return true;
        }else{
            return false;
        }
    }
        
    /**
     * connect
     *
     * @return void
     */
    function connect(){
        if($this->user != "" and $this->password != ""){
            $url = "mongodb://".$this->user.":".$this->password."@".$this->host.":".$this->port."/".$this->db_name;
        }else{
            $url = "mongodb://".$this->host.":".$this->port."/".$this->db_name;
        }
        
        try{
            $this->mongo = new MongoDB\Driver\Manager($url);
        }catch(MongoDB\Driver\Exception\Exception $e){
            die($e->getMessage());
        }
    }
        
    /**
     * collection
     *
     * @param  mixed $collection
     * @return void
     */
    public function collection($collection){
        $this->collection = $collection;
    }
        
    /**
     * clear
     *
     * @return void
     */
    function clear(){
        $this->collection = null;
    }
    
    /**
     * insert
     *
     * @param  mixed $data
     * @return void
     */
    public function insert($data = array()){
        $insert = new MongoDB\Driver\BulkWrite();
        $insert->insert($data);
        $result = $this->mongo->executeBulkWrite($this->db_name.".".$this->collection, $insert);
        return $result->getInsertedCount();
        $this->clear();
    }

    /**Example : 
    * name'i Ali olanların name'ini Mehmet olarak değiştir : 
    * updateAll(array("name" => "Ali"), array("name" => "Mehmet"));
    */
    public function updateAll($filter, $data){
        $update = new MongoDB\Driver\BulkWrite();
        $update->update($filter, ['$set' => $data], ['multi' => true]);
        $result = $this->mongo->executeBulkWrite($this->db_name.".".$this->collection, $update);
        return $result->getModifiedCount();
        $this->clear();
    }
    /**Example : 
    * name'i Ali olan tek bir kaydın name'ini Mehmet olarak değiştir : 
    * update(array("name" => "Ali"), array("name" => "Mehmet"));
    */
    public function update($filter, $data){
        $update = new MongoDB\Driver\BulkWrite();
        $update->update($filter, ['$set' => $data]);
        $result = $this->mongo->executeBulkWrite($this->db_name.".".$this->collection, $update);
        return $result->getModifiedCount();
        $this->clear();
    }
    
    /**
     * deleteAll
     *
     * @param  mixed $filter
     * @return void
     */
    public function deleteAll($filter){
        $delete = new MongoDB\Driver\BulkWrite();
        $delete->delete($filter);
        $result = $this->mongo->executeBulkWrite($this->db_name.".".$this->collection, $delete);
        $this->clear();
    }
    
    /**
     * delete
     *
     * @param  mixed $filter
     * @return void
     */
    public function delete($filter){
        $delete = new MongoDB\Driver\BulkWrite();
        $delete->delete($filter, ["limit" => true]);
        $result = $this->mongo->executeBulkWrite($this->db_name.".".$this->collection, $delete);
        $this->clear();
    }
    
    /**
     * query
     *
     * @param  mixed $filter
     * @param  mixed $options
     * @return void
     */
    public function query($filter, $options = ""){
        if(is_array($options)){
            $query = new MongoDB\Driver\Query($filter, $options);
        }else{
            $query = new MongoDB\Driver\Query($filter);
        }
        $result = $this->mongo->executeQuery($this->db_name.".".$this->collection, $query);
        return $result->toArray();
        $this->clear();
    }
    
}
