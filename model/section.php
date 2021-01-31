<?php
class Section{

    private $conn;
    private $table_name = "section";
    public $box_id;
    public $prev_section;
    public $next_section;
    public $id;
    public function __construct($db){
        $this->conn = $db;
    }

    function create(){
    
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                box_id=".$this->box_id." ; ";
    
        $stmt = $this->conn->prepare($query);
    

        echo "creating section\r\n";
        if($stmt->execute()){
            
            return true;
        }
        echo "creating section\r\n";
        return false;
    }


    function readByBoxId($count ,$boxId ){
 
        // select all query
        $query = "SELECT * FROM
                `" . $this->table_name . "` as t where t.boxId=".$boxId." limit ".$count." ;";
     
        // prepare query statement
        $stmt = $this->conn->prepare($query);
     
        // execute query
        $result = $stmt->execute();
     
        return  $stmt->fetchAll();
    }

    function deleteByID(){

        $query = "DELETE FROM 
                    " . $this->table_name . "
                WHERE
                id=". $this->id .";";
    
        $stmt = $this->conn->prepare($query);
    

        echo "removing section\r\n";
        if($stmt->execute()){
            return true;
        }
        echo "removing section\r\n";
        return false;
    }

    



}
