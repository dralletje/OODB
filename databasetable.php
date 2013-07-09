<?php 
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
