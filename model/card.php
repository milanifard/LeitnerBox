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

        echo "creating section\r\n";
        if($stmt->execute()){
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        echo "creating section\r\n";
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
                    back_audio_name = :back_audio_name,
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
        $this->section_id=htmlspecialchars(strip_tags($this->section_id));
        $this->front_text=htmlspecialchars(strip_tags($this->front_text));
        $this->back_text=htmlspecialchars(strip_tags($this->back_text));
        $this->front_image_name=htmlspecialchars(strip_tags($this->front_image_name));
        $this->front_audio_name=htmlspecialchars(strip_tags($this->front_audio_name));
        $this->back_image_name=htmlspecialchars(strip_tags($this->back_image_name));
        $this->back_audio_name=htmlspecialchars(strip_tags($this->back_audio_name));
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
