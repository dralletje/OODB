<?
## Databaserow interface
Interface DatabaseRow {
    public function __construct($table, $info);
    public function info();
    public function __get($name);
    public function __toString();
}
