<?php 
include(dirname(__FILE__).'/mysqltable.php');

## Represents a mysql database
class MysqlDatabase extends Database {
    private $connection;
    private $tables = array();

    public function __construct($host, $base, $user, $pass) {
        parent::setTableClass("MysqlTable");
        $this->connection = new mysqli($host, $user, $pass, $base);
    }
  
    public function connection() {
        return $this->connection;
    }
    
    public function create($name, $info, $additional) {
        $query = "CREATE TABLE %s (%s);";
        $rows = array();
        
        foreach(array_keys($info) as $row) {
            $rows[] = $row ." ". $info[$row];
        }
        $sql = sprintf($query, $name, implode(", ", $rows));
        return $this->connection->query($sql);
    }
}
?>
