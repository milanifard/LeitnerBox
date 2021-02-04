<?php
class Box{

    private $conn;
    private $table_name = "box";
    public $title;
    public $ownerId;
    public $description_text;
    public $default_section;
    public $id;
    public $created_at;


    public function __construct($db){
        $this->conn = $db;
    }

    function create(){
    
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                title=:title, description_text=:description_text , ownerId=:ownerId , created_at=:created_at";
    
        $stmt = $this->conn->prepare($query);
    
        $this->sanitize();
        $this->bind_values($stmt);
        $this->created_at = date("Y-m-d G:i:s");
        $stmt->bindParam(":created_at",$this->created_at );


        echo "creating box\r\n";
        if($stmt->execute()){
            $this->id = $this->conn->lastInsertId();
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

    function readById($id ){
 
        $query = "SELECT * FROM `". $this->table_name ."` as b WHERE
                b.id = '". $id ."'
                ;";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        // var_dump($row );


         
        // set values to object properties
        $this->id = $row['id'];
        $this->title = $row['title'];
        $this->description_text = $row['description_text'];
        $this->ownerId = $row['ownerId'];
        $this->default_section = $row['default_section'];
        $this->created_at = $row['created_at'];

        if( $stmt->rowCount() > 0){
            return true;
        }
        return false;
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

    function update(){
    
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    title = :title,
                    ownerId = :ownerId,
                    description_text = :description_text,
                    default_section = :default_section
                WHERE
                    id = :id";

        $stmt = $this->conn->prepare($query);
    
        $this->sanitize();
        $stmt->bindParam(":title", $this->title );
        $stmt->bindParam(":description_text", $this->description_text);
        $stmt->bindParam(":ownerId", $this->ownerId);
        $stmt->bindParam(":default_section", $this->default_section);
        $stmt->bindParam(":id", $this->id );

        if($stmt->execute()){
            $stmt->debugDumpParams();
            return true;
        }
    
        return false;
    }

    
    function sanitize(){
        $this->id=htmlspecialchars(strip_tags($this->id));
        $this->title=htmlspecialchars(strip_tags($this->title));
        $this->description_text=htmlspecialchars(strip_tags($this->description_text));
        $this->ownerId=htmlspecialchars(strip_tags($this->ownerId));
        $this->default_section=htmlspecialchars(strip_tags($this->default_section));
    }

    function bind_values($stmt){
        
        $stmt->bindParam(":title", $this->title );
        $stmt->bindParam(":description_text", $this->description_text);
        $stmt->bindParam(":ownerId", $this->ownerId);
    }

}
