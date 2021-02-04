<?php
class Section{

    private $conn;
    private $table_name = "section";
    public $box_id;
    public $prev_section;
    public $next_section;
    public $id;
    public $created_at;
    
    public function __construct($db){
        $this->conn = $db;
    }

    function create(){
    
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                box_id=".$this->box_id.", created_at=:created_at ; ";
    
        $stmt = $this->conn->prepare($query);
        $this->created_at = date("Y-m-d G:i:s");
        $stmt->bindParam(":created_at",$this->created_at );

        echo "creating section\r\n";
        if($stmt->execute()){
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        echo "creating section\r\n";
        return false;
    }


    function readByBoxId($count ,$box_id ){
 
        // select all query
        $query = "SELECT * FROM
                `" . $this->table_name . "` as t where t.box_id=".$box_id." limit ".$count." ;";
     
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

    
    function update(){
    
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    box_id = :box_id,
                    prev_section = :prev_section,
                    next_section = :next_section,
                WHERE
                    id = :id";

        $stmt = $this->conn->prepare($query);
    
        $this->sanitize();
        $this->bind_values($stmt);
    
        if($stmt->execute()){
           
            return true;
        }
    
        return false;
    }

        
    function sanitize(){
        $this->box_id=htmlspecialchars(strip_tags($this->box_id));
        $this->prev_section=htmlspecialchars(strip_tags($this->prev_section));
        $this->next_section=htmlspecialchars(strip_tags($this->next_section));
        $this->box_id=htmlspecialchars(strip_tags($this->box_id));
    }

    function bind_values($stmt){
        $stmt->bindParam(":box_id", $this->box_id);
        $stmt->bindParam(":description_text", $this->description_text);
        $stmt->bindParam(":next_section", $this->next_section);
        $stmt->bindParam(":id", $this->id);
    }



}
