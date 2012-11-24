<?
## Databaserow interface
class MysqlRow {
    private $info = array();
    private $table;
    
    public function __construct($table, $info) {
        $this->info = $info;
        $this->table = $table;
    }
    
    public function info() {
        return $this->info;
    }
    
    public function __get($name) {
        if(!array_key_exists($name, $this->info)) {
            die("WAOH");
        }
        return $this->info[$name];
    }
    public function __toString() { return xreturn($info); }
}
