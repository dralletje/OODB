<?php 
## Databasetable interface
Interface DatabaseTable {
    # Initializes the table, this is only called by the __get function from the database
    public function __construct($database, $name);

    # Get awesome info about the table, like collumns and such
    public function info();
    
    # The parent database
    public function database();

    # Search the table for rows matching this where array
    public function find($where, $sql);
    # Same as above, but just returns one or null
    public function findOne($where, $sql);
        
    # Delete the rows that match this info
    public function delete($info);
    
    # Insert a row with the info given, if something is not set it will be NULL
    public function insert($info);
    
    # Set all info given on all rows matching the where array
    public function update($where, $info);
}
?>