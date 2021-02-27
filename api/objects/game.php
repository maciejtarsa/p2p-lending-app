<?php

class Game{
 
    // database connection and table name
    private $conn;
    private $table_name = "game";
 
    // object properties
    public $game_id;
    public $name;
    public $description;
    public $value;
    public $email;
    public $status;
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
    
    // get a list of games with their details
    function readGameIDs(){
    // select query
    $query = "SELECT
                `game_id`, `name`, `description`, `value`, `status`
            FROM
                " . $this->table_name . "
            WHERE
                email='".$this->email."'
            ORDER BY
                name";
    // prepare query statement
    $stmt = $this->conn->prepare($query);
    // sanitize
    $this->game_id=htmlspecialchars(strip_tags($this->game_id));
    $this->name=htmlspecialchars(strip_tags($this->name));
    $this->description=htmlspecialchars(strip_tags($this->description));
    $this->value=htmlspecialchars(strip_tags($this->value));
    $this->status=htmlspecialchars(strip_tags($this->status));
    // bind values
    $stmt->bindParam(":game_id", $this->game_id);
    $stmt->bindParam(":name", $this->name);
    $stmt->bindParam(":description", $this->description);
    $stmt->bindParam(":value", $this->value);
    $stmt->bindParam(":status", $this->status);
    // execute query
    $stmt->execute();
    return $stmt;
    }
    
    // get details of a particular game
    function readOne() {
    // select all query
    $query = "SELECT
                `game_id`, `name`, `description`, `value`, `status`
            FROM
                " . $this->table_name . "
            WHERE
                game_id='".$this->game_id."'";
    // prepare query statement
    $stmt = $this->conn->prepare($query);
    // sanitize
    $this->game_id=htmlspecialchars(strip_tags($this->game_id));
    $this->name=htmlspecialchars(strip_tags($this->name));
    $this->description=htmlspecialchars(strip_tags($this->description));
    $this->value=htmlspecialchars(strip_tags($this->value));
    $this->status=htmlspecialchars(strip_tags($this->status));
    
    // bind values
    $stmt->bindParam(":game_id", $this->game_id);
    $stmt->bindParam(":name", $this->name);
    $stmt->bindParam(":description", $this->description);
    $stmt->bindParam(":value", $this->value);
    $stmt->bindParam(":status", $this->status);
    // execute query
    $stmt->execute();
    return $stmt;
    }
    
    // add a new game
    function addGame() {
    $query = "INSERT INTO
                " . $this->table_name . "
            SET
                name=:name, description=:description, value=:value, email=:email, status=:status";
    // prepare query
    $stmt = $this->conn->prepare($query);
    // sanitize
    $this->name=htmlspecialchars(strip_tags($this->name));
    $this->description=htmlspecialchars(strip_tags($this->description));
    $this->value=htmlspecialchars(strip_tags($this->value));
    $this->email=htmlspecialchars(strip_tags($this->email));
    $this->status=htmlspecialchars(strip_tags($this->status));
    // bind values
    $stmt->bindParam(":name", $this->name);
    $stmt->bindParam(":description", $this->description);
    $stmt->bindParam(":value", $this->value);
    $stmt->bindParam(":email", $this->email);
    $stmt->bindParam(":status", $this->status);
    // execute query
    if($stmt->execute()){
        $game_id = $this->conn->lastInsertId();
        return $game_id;
    }
    return false;
    }
    
    // update an existing game
    function updateGame() {
    $query = "UPDATE
                " . $this->table_name . "
            SET
                name='".$this->name."', description='".$this->description."', value='".$this->value."', status='".$this->status."'
            WHERE
                game_id='".$this->game_id."'";
    // prepare query
    $stmt = $this->conn->prepare($query);
    // sanitize
    $this->game_id=htmlspecialchars(strip_tags($this->game_id));
    $this->name=htmlspecialchars(strip_tags($this->name));
    $this->description=htmlspecialchars(strip_tags($this->description));
    $this->value=htmlspecialchars(strip_tags($this->value));
    $this->status=htmlspecialchars(strip_tags($this->status));
    // bind values
    $stmt->bindParam(":game_id", $this->game_id);
    $stmt->bindParam(":name", $this->name);
    $stmt->bindParam(":description", $this->description);
    $stmt->bindParam(":value", $this->value);
    $stmt->bindParam(":status", $this->status);
    // execute query
    if($stmt->execute()){
        return true;
    }
    return false;
    
    }
    
    // delete an existing game
    function deleteGame() {
    $query = "DELETE FROM
                " . $this->table_name . "
            WHERE
                game_id='".$this->game_id."'";
    // prepare query
    $stmt = $this->conn->prepare($query);
    // execute query
    if($stmt->execute()){
        return true;
    }
    return false;
    
    }
    
}