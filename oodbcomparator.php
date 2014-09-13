<?php
// Make comparison arrays, in a chained manner
// eg.

include_once('lib/iarray.php');

class OodbComparator extends OodbArray {
  protected $comparisons = array();

  public function __construct() {
    parent::__construct($this->comparisons);
  }

  public function __get($name) {
    $var = parent::__get($name);
    return $var;
  }

  public function __call($name, $arguments) {
    if( !isset($arguments[0]) )
      return $this;

    $this->comparisons['$'.$name] = $arguments[0];
    return $this;
  }

  public function raw() {
    return $this->comparisons;
  }
}
?>
