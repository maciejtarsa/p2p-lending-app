<?php

// functions based on
// https://codinginfinite.com/crud-operations-php-mysql-tutorials-source-code/


class User_details{
 
    // database connection and table name
    private $conn;
    private $table_name = "user_details";
 
    // object properties
    public $email;
    public $name;
    public $address1;
    public $address2;
    public $town;
    public $postcode;
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
    
    // update user_details 
    function update(){
    
        // query to update record
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    name='".$this->name."', address1='".$this->address1."', address2='".$this->address2."', town='".$this->town."', postcode='".$this->postcode."'
                WHERE
                    email='".$this->email."'";
        
        // prepare query
        $stmt = $this->conn->prepare($query);
        // sanitize
        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->address1=htmlspecialchars(strip_tags($this->address1));
        $this->address2=htmlspecialchars(strip_tags($this->address2));
        $this->town=htmlspecialchars(strip_tags($this->town));
        $this->postcode=htmlspecialchars(strip_tags($this->postcode));
        // bind values
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":address1", $this->address1);
        $stmt->bindParam(":address2", $this->address2);
        $stmt->bindParam(":town", $this->town);
        $stmt->bindParam(":postcode", $this->postcode);
        // execute query
        if($stmt->execute()){
            return true;
        }
        return false;
    }
    
    // read user details
    function readDetails(){
    // select all query
    $query = "SELECT
                `email`, `name`, `address1`, `address2`, `town`, `postcode`
            FROM
                " . $this->table_name . " 
            WHERE
                email='".$this->email."'";
    // prepare query statement
    $stmt = $this->conn->prepare($query);
    // sanitize
    $this->email=htmlspecialchars(strip_tags($this->email));
    $this->name=htmlspecialchars(strip_tags($this->name));
    $this->address1=htmlspecialchars(strip_tags($this->address1));
    $this->address2=htmlspecialchars(strip_tags($this->address2));
    $this->town=htmlspecialchars(strip_tags($this->town));
    $this->postcode=htmlspecialchars(strip_tags($this->postcode));
    // bind values
    $stmt->bindParam(":email", $this->email);
    $stmt->bindParam(":name", $this->name);
    $stmt->bindParam(":address1", $this->address1);
    $stmt->bindParam(":address2", $this->address2);
    $stmt->bindParam(":town", $this->town);
    $stmt->bindParam(":postcode", $this->postcode);    
    // execute query
    $stmt->execute();
    return $stmt;
    }

}