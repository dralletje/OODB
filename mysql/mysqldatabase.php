<?
import('lib.oopdb.database');
import('lib.oopdb.mysql.*');
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
  
  public function query($query, $returnarray, $whereargs=array()) {
    $whereclausule = "";
    $types = "";
    $params = array();
    $bind_result_params = array();
    $isfirst = true;
  
    foreach($infoarray as $whereargs => $value) {
            if(gettype($value) == "array") {
                die("not supported yet");
            }

            if($isfirst) {
                $isfirst = false;
                $whereclausule .= " WHERE `{$key}`=?";
            } else {
                $whereclausule .= " && `{$key}`=?";
            }
            
            $type = gettype($value);
            $types .= substr($type, 0, 1);
            $params[] = &$infoarray[$key];
    }

    foreach($returnarray as $key) {
        $bind_result_params[] = &$results[strtolower($key)];
    }
  
    if (!$mysqli_exec = $this->connection->prepare($query)) {
        die(mysqli_error($this->connection));
    }
    call_user_func_array(array($mysqli_exec, 'bind_param'), $func_args);
    $mysqli_exec->execute();
    call_user_func_array(array($mysqli_exec, 'bind_result'), $bind_result_params); 
  }
}
