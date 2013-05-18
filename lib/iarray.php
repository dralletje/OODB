<?php
class OodbArray implements arrayaccess, Iterator {
    protected $container = array();
    protected $pointer = 0;
    
    protected function __construct(&$array) {
        $this->container = &$array;
    }

    
    // Object get methods
    public function __get($name) {
        $this->isCalled();
        if(!array_key_exists($name, $this->container)) {
            return null;
        }
        
        return $this->container[$name];
    }
    
    public function __set($name, $value) {
        $this->isCalled();
        $this->container[$name] = $value;
    }

    public function __isset($name) {
        $this->isCalled();
        return isset($this->container[$name]);
    }

    public function __unset($name) {
        $this->isCalled();
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
        $keys = $this->arrayKeys();
        return $this->__get( $keys[ $this->pointer ] );
    }
    
    public function valid() {
        $this->isCalled();
        return ( count($this->container) > $this->pointer ); 
    }
    
    
    public function key() {
        $this->isCalled();
        $keys = $this->arrayKeys();
        return $keys[ $this->pointer ];
    }
    public function next() {
        $this->isCalled();
        $this->pointer++;
    }
    public function rewind() {
        $this->isCalled();
        $this->pointer = 0;
    }
    public function seek($position) {
        $this->isCalled();
        $this->pointer = $position;
    }
    
    /* Internal method to create sort of events */
    private function isCalled() {
        if( !method_exists( $this, "onCall" ) ) return;
        $this->onCall();
    }
    
    private function arrayKeys() {
        return array_keys( $this->container );
    }
}
?>
