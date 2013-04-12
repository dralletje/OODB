<?php
## Database interface

Interface Database {
  # Creates a new database connection
  public function __construct($host, $table, $user, $pass);
  
  # Get the table you want to query on
  public function __get($tablename);
  
  # Returns the raw connection made
  public function connection();
}
?>