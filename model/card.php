<?php
class Card{

    private $conn;
    private $table_name = "card";
    public $section_id;
    public $front_text;
    public $back_text;
    public $front_image_name;
    public $front_audio_name;
    public $back_image_name;
    public $back_audio_name;
    public $id;
    public $created_at;
    public function __construct($db){
        $this->conn = $db;
    }

    function create(){
    
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                section_id = :section_id,
                front_text = :front_text,
                back_text = :back_text,
                front_image_name = :front_image_name,
                front_audio_name = :front_audio_name,
                back_image_name = :back_image_name,
                created_at = :created_at,
                back_audio_name = :back_audio_name; ";
    
        $stmt = $this->conn->prepare($query);

        

        $this->sanitize();
        $stmt->bindParam(":section_id", $this->section_id);
        $stmt->bindParam(":front_text", $this->front_text);
        $stmt->bindParam(":back_text", $this->back_text);
        $stmt->bindParam(":front_image_name", $this->front_image_name);
        $stmt->bindParam(":front_audio_name", $this->front_audio_name);
        $stmt->bindParam(":back_image_name", $this->back_image_name);
        $stmt->bindParam(":back_audio_name", $this->back_audio_name);
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

    function readById($id ){
 
        $query = "SELECT * FROM `". $this->table_name ."` as c WHERE
                c.id = '". $id ."'
                ;";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        var_dump($row );


         
        // set values to object properties
        $this->id = $row['id'];
        $this->section_id = $row['section_id'];
        $this->front_text = $row['front_text'];
        $this->back_text = $row['back_text'];
        $this->front_image_name = $row['front_image_name'];
        $this->front_audio_name = $row['front_audio_name'];
        $this->back_image_name = $row['back_image_name'];
        $this->back_audio_name = $row['back_audio_name'];
        $this->created_at = $row['created_at'];

        if( $stmt->rowCount() > 0){
            return true;
        }
        return false;
    }


    function readBySectionId($count ,$section_id ){
 
        // select all query
        $query = "SELECT * FROM
                `" . $this->table_name . "` as t where t.section_id=".$section_id." limit ".$count." ;";
     
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
    

        echo "removing card\r\n";
        if($stmt->execute()){
            return true;
        }
        echo "removing card\r\n";
        return false;
    }

    
    function update(){
    
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    section_id = :section_id,
                    front_text = :front_text,
                    back_text = :back_text,
                    front_image_name = :front_image_name,
                    front_audio_name = :front_audio_name,
                    back_image_name = :back_image_name,
                    back_audio_name = :back_audio_name
                WHERE
                    id = :id; ";

        $stmt = $this->conn->prepare($query);
    
        $this->sanitize();
        $this->bind_values($stmt);
    
        if($stmt->execute()){
           
            return true;
        }
    
        return false;
    }

        
    function sanitize(){
        $this->id = intval($this->id);
        $this->section_id=htmlspecialchars(strip_tags($this->section_id));
        $this->front_text=htmlspecialchars(strip_tags($this->front_text));
        $this->back_text=htmlspecialchars(strip_tags($this->back_text));
        $this->front_image_name=htmlspecialchars(strip_tags($this->front_image_name));
        $this->front_audio_name=htmlspecialchars(strip_tags($this->front_audio_name));
        $this->back_image_name=htmlspecialchars(strip_tags($this->back_image_name));
        $this->back_audio_name=htmlspecialchars(strip_tags($this->back_audio_name));
        $this->id=htmlspecialchars(strip_tags($this->id));

    }

    function bind_values($stmt){
        $stmt->bindParam(":section_id", $this->section_id);
        $stmt->bindParam(":front_text", $this->front_text);
        $stmt->bindParam(":back_text", $this->back_text);
        $stmt->bindParam(":front_image_name", $this->front_image_name);
        $stmt->bindParam(":front_audio_name", $this->front_audio_name);
        $stmt->bindParam(":back_image_name", $this->back_image_name);
        $stmt->bindParam(":back_audio_name", $this->back_audio_name);
        $stmt->bindParam(":id", $this->id);
    }



}
