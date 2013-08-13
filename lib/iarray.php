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
        if(!array_key_exists($name, $this->container)) return null;
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
        return $this->__set($offset, $value);
    }
    public function offsetExists($offset) {
        return $this->__isset($offset);
    }
    public function offsetUnset($offset) {
        return $this->__unset($offset);
    }
    public function offsetGet($offset) {
        return $this->__get($offset);
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
    
    private function fn($fn) {
        if( !is_callable($fn) ) return function($x) { return $x; };
        return $fn;
    }
    
    /* Underscore JS like functions */
    // These functions MAY NOT use eachother, so they are fully independent.
    // All MAY NOT modify the origional array.
    public function each($fn) {
        $this->isCalled();
        $fn = $this->fn($fn);
        foreach( $this->container as $key => $value ) {
            $fn($key, $value, $this->container);
        }
    }
    
    public function map($fn) {
        $this->isCalled();
        $fn = $this->fn($fn);
        $new = array();
        foreach( $this->container as $key => $value ) {
            $new[] = $fn($key, $value, $this->container);
        }
        return $new;
    }
    
    public function reduce($fn, $memo) {
        $this->isCalled();
        foreach( $this->container as $key => $value ) {
            $memo = $fn($memo, $value, $key, $this->container);
        }
        return $memo;
    }
    
    public function filter($fn = null) {
        $this->isCalled();
        $fn = $this->fn($fn);
        $new = array();
        foreach( $this->container as $key => $value ) {
            if( !$fn($value, $key, $this->container) ) continue;
            $new[ $key ] = $value;
        }
        return $new;
    }
        
    public function reject($fn = null) { // Opposite of filter
        $this->isCalled();
        $fn = $this->fn($fn);
        $new = array();
        foreach( $this->container as $key => $value ) {
            if( $fn($value, $key, $this->container) ) continue;
            $new[ $key ] = $value;
        }
        return $new;
    }
    
    public function toArray() {
        $this->isCalled();
        return iterator_to_array($this->container);
    }
    
    /* My own additions */
    public function print_r($donotprint = false) {
      $this->isCalled();
      print_r($this->container, $true);
    }
}

if( ! class_exists("iArray") ) {
    class_alias("OodbArray", "iArray");
}
?>
