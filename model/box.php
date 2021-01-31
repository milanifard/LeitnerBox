<?php
class Box{

    private $conn;
    private $table_name = "box";
    public $title;
    public $ownerId;
    public $description_text;
    public $id;
    public function __construct($db){
        $this->conn = $db;
    }

    function create(){
    
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                title=:title, description_text=:description_text , ownerId=:ownerId";
    
        $stmt = $this->conn->prepare($query);
    
        $this->sanitize();
        $this->bind_values($stmt);
        echo "creating box\r\n";
        if($stmt->execute()){
            return true;
        }
        echo "creating box2\r\n";
        return false;
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
    

        echo "removing box\r\n";
        if($stmt->execute()){
            return true;
        }
        echo "removing box2\r\n";
        return false;
    }

    
    function sanitize(){
        $this->title=htmlspecialchars(strip_tags($this->title));
        $this->description_text=htmlspecialchars(strip_tags($this->description_text));
        $this->ownerId=htmlspecialchars(strip_tags($this->ownerId));
    }

    function bind_values($stmt){
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":description_text", $this->description_text);
        $stmt->bindParam(":ownerId", $this->ownerId);
    }

}
