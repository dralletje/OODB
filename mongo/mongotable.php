<?php
## represents a Mysql Table in a Mysql Database

include('sqlhelper.php');

class MongoTable extends OodbDatabaseTable {
    private $connection;
    private $name;
    private $collection;


    public function __construct($database, $name) {
       parent::setDatabase($database);
       $this->connection = $this->database->connection();
       $this->name = $name;
       $this->collection = $this->connection->$name;
    
       // Populate the fields array with fields.. wait it's mongodb
    }

    public function info() {
        return "Mongodb is awesome";
    }
    
    /* Parse the OODB cursor and execute it */
    public function executeOodbCursor($cursor) {   
        /* Where */
        $mcursor = $this->collection->find($cursor->where);

        /* Limit */
        if( $cursor->limit !== 0 )
            $mcursor->limit($cursor->limit);
        
        /* Sort */
        if( count($cursor->sort) !== 0 ) 
            $mcursor->sort($cursor->sort);

        /* To get the results */
        $results = iterator_to_array($mcursor);
        //print_r($results);
        return $results;
    }


    public function insert($info_placeholder) {
        die("sorry");
    }

    public function update($where, $info) {
        die("sorry");
    }
    
    public function delete($where) {    
        die("sorry");
    }

}

?>
