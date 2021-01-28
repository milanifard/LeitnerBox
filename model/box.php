<?php
class Box{

    private $conn;
    private $table_name = "box";
    public $title;
    public $description_text;

    public function __construct($db){
        $this->conn = $db;
    }

    function create(){
    
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                title=:title, description_text=:description_text";
    
        $stmt = $this->conn->prepare($query);
    
        $this->sanitize();
        $this->bind_values($stmt);
       
        if($stmt->execute()){
            return true;
        }
    
        return false;
        
    }

    
    function sanitize(){
        $this->title=htmlspecialchars(strip_tags($this->title));
        $this->description_text=htmlspecialchars(strip_tags($this->description_text));
    }

    function bind_values($stmt){
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":description_text", $this->description_text);
    }

}
