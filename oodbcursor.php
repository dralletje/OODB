<?php

include_once('lib/iarray.php');

class AlreadyExecutedException extends Exception {
    protected $message = "OODB query already executed";
}

class OodbCursor extends OodbArray {
  public $where = array();
  public $limit = 0;
  public $sort = array();

  private $table;
  private $results;
  private $executed = false;

  public function __construct($table, $where) {
    parent::__construct($this->results);

    $this->table = $table;
    $this->where = $where;
  }

  /* Internal state checking functions */
  public function isInPreQueryState() {
    if( $this->executed !== false )
      throw new AlreadyExecutedException();
  }

  /* PreQueryState methods */
  public function limit($limit) {
    $this->isInPreQueryState();
    $this->limit = $limit;
    return $this;
  }

  public function sort($sort) {
    $this->isInPreQueryState();
    $this->sort = $sort;
    return $this;
  }

  /* Execute the query and store the results */
  public function ensureResults() {
    if( isset($this->results) ) return;

    $this->results = $this->table->executeOodbCursor($this);
    $this->executed = true;
  }

  /* Just a public alias for ensureResults */
  public function run() {
  ensureResults();
  return $this;
  }

  /* Get a index, so execute the query */
  public function __get($name) {
    return parent::__get($name);
  }

  public function __isset($name) {
    return parent::__isset($name);
  }

  /* Just do nothing when trying to set xd */
  public function __unset($var) {
    return false;
  }
  public function __set($var, $var2) {
    return false;
  }

  protected function onCall() {
    $this->ensureResults();
  }
}

?>
