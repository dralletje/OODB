<?php 

## Represents a mongo database
class MongoDatabase extends OodbDatabase {
    private $connection;
    private $tables = array();

    public function __construct($host, $base, $user, $pass, $port=null) {
        parent::setTableClass("MongoTable");
        
        $auth = "";
        if( isset($user) && isset($pass) ) 
            $auth = "{$user}:{$pass}@";
            
        $m = new MongoClient("mongodb://{$auth}{$host}/");
        $this->connection = $m->selectDB($base);
    }
  
    public function connection() {
        return $this->connection;
    }
    
    public function create($name, $info, $additional) {
        throw new Exception("Sorry");
    }
}
?>
