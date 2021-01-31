<?php
class Box{

    private $conn;
    private $table_name = "section";
    public $box_id;
    public $prev_section;
    public $next_section;
    public $id;
    public function __construct($db){
        $this->conn = $db;
    }

    function readByOwnerId($count ,$ownerId ){
 
        // select all query
        $query = "SELECT * FROM
                `" . $this->table_name . "` as t where t.ownerId=".$ownerId." limit ".$count." ;";
     
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

    
    function sanitize(){
        $this->box_id=htmlspecialchars(strip_tags($this->box_id));
        $this->prev_section=htmlspecialchars(strip_tags($this->prev_section));
        $this->next_section=htmlspecialchars(strip_tags($this->next_section));
    }

    function bind_values($stmt){
        $stmt->bindParam(":box_id", $this->box_id);
        $stmt->bindParam(":prev_section", $this->prev_section);
        $stmt->bindParam(":next_section", $this->next_section);
    }

}
