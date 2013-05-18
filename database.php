<?php
## Database interface

abstract class Database {
    # Creates a new database connection
    public abstract function __construct($host, $database, $user, $pass);
    
    # Returns the raw connection made
    public abstract function connection();
    
    # Create a table
    public abstract function create($name, $info, $additional);
    

    /* Get the table you want to query on */
    private $tableClass;
    private $tables = array();
    public function setTableClass($class) {
        $this->tableClass = $class;
    }
    
    public function __get($tablename) {
        if(!array_key_exists($tablename, $this->tables)) {
            $this->tables[$tablename] = new $this->tableClass($this, $tablename);
        }
        return $this->tables[$tablename];
    }
    
    
    /* Get a comparator, with static methods */
    public static function c($comparator, $value) {
        $oodbcomparator = new OodbComparator();
        return $oodbcomparator->$comparator($value);
    }
    
    public static function __callStatic($comparator, $arguments) {
        if( !isset($arguments[0]) ) return;
        return self::c($comparator, $arguments[0]);
    }
}
?>
