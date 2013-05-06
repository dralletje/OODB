<?php
// Main file to include, will allow you to simple choose a databasetype.

class OODB {
    
    // Found no better way than a static method :(
    public static function get($type, $host, $database, $user, $pass) {
        $file = dirname(__FILE__).'/'.$type.'/'.$type.'database.php';
        if(file_exists($file)) {
            include_once($file);
            $class = ucfirst($type).'Database';
            return new $class($host, $database, $user, $pass);
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
