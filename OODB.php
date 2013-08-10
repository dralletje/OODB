<?php
// Main file to include, will allow you to simple choose a databasetype.

class OODB {
    
    // Found no better way than a static method :(
    public static function get($type, $host, $database, $user=null, $pass=null) {
        $splitted = explode(":", $host);
        $host = $splitted[0];
        
        $port = null;
        if( count($splitted) === 2) $port = $splitted[1];
        
        if( count($splitted) > 2) new Exception("Bad host $host.");
    
        $dir = dirname(__FILE__);
        $file = $dir.'/'.$type.'/'.$type;
        
        if(file_exists($file.'database.php')) {
            // Include the parents
            include_once($dir.'/oodbcursor.php');
            include_once($dir.'/oodbcomparator.php');
        
            // Include the interfaces
            include_once($dir.'/oodbdatabase.php');

            // Include database classes
            include_once($file.'database.php');
            include_once($file.'table.php');
            
            $class = ucfirst($type).'Database';
            return new $class($host, $database, $user, $pass, $port);
        } else {
            return false;
        }
    }
    
    /**  As of PHP 5.3.0  */
    // You can use the database name as function name
    public static function __callStatic($name, $arguments) {
        return call_user_func_array(array(__CLASS__, 'get'), array_merge(array($name), $arguments));
    }
}
