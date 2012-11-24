<?
## Databasetable interface
Interface DatabaseTable {
    public function __construct($database, $name);

    public function info();
    public function database();

    public function find($where);
    public function delete($info);
    public function insert($info);
    public function update($where, $info);
}
