<?
include('../database.php');
include('mysqltable.php');

## Represents a mysql database
class MysqlDatabase implements Database {
  private $connection;
  private $tables = array();

  public function __construct($host, $base, $user, $pass) {
    $this->connection = new mysqli($host, $user, $pass, $base);
  }

  public function __get($tablename) {
    if(!array_key_exists($tablename, $this->tables)) {
      $this->tables[$tablename] = new MysqlTable($this, $tablename);
    }
    return $this->tables[$tablename];
  }
  
  public function connection() {
    return $this->connection;
  }
  
    //
    // Some helper functions
    //
  public function createWhereClausule($infoarray, $table) {
    $whereclausule = "";
    $types = "";
    $params = array();
    
    foreach($infoarray as $key => $value) {
        if(!array_key_exists(strtolower($key), $table->fields)) {
            die("wrong row used, row {$key} does not exist in table {$table->name}.");
        }

        if(gettype($value) == "array") {
            die("not supported yet: ".xdebug($value));
        }

        if($whereclausule === "") {
            $isfirst = false;
            $whereclausule .= " WHERE `{$key}`=?";
        } else {
            $whereclausule .= " && `{$key}`=?";
        }
        
        $type = gettype($value);
        $types .= substr($type, 0, 1);
        $params[] = &$infoarray[$key];
    }
    
    $func_args = array_merge(array($types), $params);
    
    return array(
        'bind_param' => $func_args,
        'whereclausule' => $whereclausule
    }
  }
}
