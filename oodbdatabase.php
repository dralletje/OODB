<?php
## Database interface
abstract class OodbDatabase {
  # Creates a new database connection
  public abstract function __construct($host, $database,$user, $pass, $port);

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
    if(!array_key_exists($tablename, $this->tables))
      $this->tables[$tablename] = new $this->tableClass($this, $tablename);
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

## Databasetable interface
abstract class OodbDatabaseTable {
  # Initializes the table, this is only called by the __get function from the database
  public abstract function __construct($database, $name);

  # Get awesome info about the table, like collumns and such
  public abstract function info();

  # Delete the rows that match this info
  public abstract function delete($info);

  # Insert a row with the info given, if something is not set it will be NULL
  public abstract function insert($info);

  # Set all info given on all rows matching the where array
  public abstract function update($where, $info);

  # Execute the cursor, with where, limit and sort.
  public abstract function executeOodbCursor($cursor);


  /* Create the OODB Cursor */
  public function find($where=array()) {
    return new OodbCursor($this, $where);
  }
  // Just return one result
  public function findOne($where=array()) {
    $results = $this->find($where)->limit(1);
    if( iterator_count($results) !== 1 ) return null;
    return $results[0];
  }


  /* Return the parent database */
  protected $database;
  public function setDatabase($database) {
    $this->database = $database;
  }
  public function database() {
    return $this->database;
  }
}
?>
