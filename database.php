<?
## Database interface

Interface Database {
  public function __construct($host, $table, $user, $pass);
  public function __get($tablename);
  public function connection();
  public function query($query, $returnarray, $whereargs=array());
}
