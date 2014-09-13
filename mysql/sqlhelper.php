<?php
    //
    // Some helper functions
    //

class SqlHelper {
  static public function createWhereClausule($where, $table) {
    return self::where($where, $table);
  }

  static public function where($where, $table) {
    $whereclausule = "";
    $types = "";
    $params = array();

    foreach($where as $key => $comparearray) {
      if(!array_key_exists(strtolower($key), $table->fields))
        throw new Exception("wrong row used, row {$key} does not exist in table {$table->name}.");

      /* If it is a OodbComparator object, get the comparations */
      if( gettype($comparearray) == "object" )
        $comparearray = $comparearray->raw();

      if(gettype($comparearray)  != "array")
        $comparearray = array('$is' => $comparearray);

      // Can't handle multie comparations at once yet
      if( count($comparearray) !== 1 ) {
        throw new Exception("Ugh :( Can't handle multiple comparations for now.");
      }

      // Map $comp to mysql comparators
      $comparators = array(
        "gte" => ">=",
        "gt" => ">",

        "lte" => "<=",
        "lt" => "<",

        "is" => "=",
        "eq" => "=",
        "equals" => "=",

        "not" => "!=",
        "isnot" => "!=",
        "like" => "LIKE"
      );

      $comparator_temp = array_keys($comparearray);
      $comparator = $comparator_temp[0];
      $value = $comparearray[ $comparator ];

      /* If it's not an comparator????? */
      if( substr($comparator, 0, 1) !== "$" )
        throw new Exception("Comparator has to start with $ ({$comparator})");

      $comparator = substr($comparator, 1);

      /* If it is an non existing comparator */
      if( !array_key_exists($comparator, $comparators) )
        throw new Exception("{$comparator} is an invalid comparator.");

      /* Non existing value type */
      if( ! in_array(gettype($value), array("integer", "double", "string", "NULL")) )
        throw new Exception("Variables type " . gettype($value) . " not yet supported");

      $mysqlcomparator = $comparators[ $comparator ];

      if($whereclausule === "")
        $whereclausule .= " WHERE `{$key}`{$mysqlcomparator}?";
      else
        $whereclausule .= " && `{$key}`{$mysqlcomparator}?";

      $type = gettype($value);
      $types .= substr($type, 0, 1);
      $params[] = $value;
    }

    $func_args = array_merge(array($types), $params);

    return array(
      'bind_param' => $func_args,
      'where_clausule' => $whereclausule
    );
  }

  static public function limit($limit, $table) {
    if( !is_int($limit) || $limit <= 0 )
      return "";
    return " LIMIT {$limit}";
  }

  static public function sort($sort, $table) {
    if( count($sort) === 0 ) return "";

    $string = " ORDER BY";
    foreach($sort as $critism => $order) {
      if(!array_key_exists(strtolower($critism), $table->fields))
          throw new Exception("wrong row used, row {$critism} does not exist in table {$table->name}.");

      $orderstring = "ASC";
      if($order < 0) $orderstring = "DESC";
      $string .= " {$critism} {$orderstring}";
    }

    return $string;
  }
}
?>
