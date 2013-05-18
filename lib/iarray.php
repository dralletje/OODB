<?php
class iArray implements arrayaccess, Iterator {
    protected $container = array();
    protected $pointer = 0;
    
    protected function __construct(&$array) {
        $this->container = &$array;
    }

    
    // Object get methods
    public function __get($name) {
        if(!array_key_exists($name, $this->container)) {
            return null;
        }
        
        return $this->container[$name];
    }
    
    public function __set($name, $value) {
        $this->container[$name] = $value;
    }

    public function __isset($name) {
        return isset($this->container[$name]);
    }

    public function __unset($name) {
        unset($this->container[$name]);
    }
    
    
    /* array to object mapping */
    public function offsetSet($offset, $value) {
        $this->__set($offset, $value);
    }
    public function offsetExists($offset) {
        $this->__isset($offset);
    }
    public function offsetUnset($offset) {
        $this->__unset($offset);
    }
    public function offsetGet($offset) {
        $this->__get($offset);
    }
    
    
    // Iterator methods
    public function current() {
        return $this->__get($this -> pointer);
    }
    
    public function valid() {
        return $this->__isset($this -> pointer);
    }
    
    
    public function key() {
        return $this->pointer;
    }
    public function next() {
        $this->pointer++;
    }
    public function rewind() {
        $this->pointer = 0;
    }
    public function seek($position) {
        $this->pointer = $position;
    }
}
?>
