<?php

// structure copied from
// https://codinginfinite.com/restful-web-services-php-example-php-mysql-source-code/
class User{
 
    // database connection and table name
    private $conn;
    private $table_name = "user_login";
    private $table_name2 = "user_details";
 
    // object properties
    public $email;
    public $password;
    public $address1;
    public $address2;
    public $town;
    public $postcode;

    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
    // register user email and password
    function signup(){
    if($this->isAlreadyExist()){
        return false;
    }
    // query to insert a record into user_login
    $query = "INSERT INTO
                " . $this->table_name . "
            SET
                email=:email, password=:password;
            INSERT INTO
                " . $this->table_name2 . "
            SET
                email=:email, name=:name, address1=:address1, address2=:address2, town=:town, postcode=:postcode";
    // prepare query
    $stmt = $this->conn->prepare($query);
    // sanitize
    $this->email=htmlspecialchars(strip_tags($this->email));
    $this->password=htmlspecialchars(strip_tags($this->password));
    $this->name=htmlspecialchars(strip_tags($this->name));
    $this->address1=htmlspecialchars(strip_tags($this->address1));
    $this->address2=htmlspecialchars(strip_tags($this->address2));
    $this->town=htmlspecialchars(strip_tags($this->town));
    $this->postcode=htmlspecialchars(strip_tags($this->postcode));
    // bind values
    $stmt->bindParam(":email", $this->email);
    $stmt->bindParam(":password", $this->password);
    $stmt->bindParam(":name", $this->name);
    $stmt->bindParam(":address1", $this->address1);
    $stmt->bindParam(":address2", $this->address2);
    $stmt->bindParam(":town", $this->town);
    $stmt->bindParam(":postcode", $this->postcode);
    // execute query
    if($stmt->execute()){
        $this->id = $this->conn->lastInsertId();
        return true;
    }
    return false;
    
    }
    
    // update pss
    // used when creating the app
    function update_pss(){
        // update query
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    password='".$this->password."'
                WHERE
                    email='".$this->email."'";
        // prepare query
        $stmt = $this->conn->prepare($query);
        // sanitize
        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->password=htmlspecialchars(strip_tags($this->password));
        // bind values
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $this->password);
        // execute query
        if($stmt->execute()){
            return true;
        }
        return false;
    
    }
    
    // login user
    function login(){
    // select all query
    $query = "SELECT
                `email`, `password`
            FROM
                " . $this->table_name . " 
            WHERE
                email='".$this->email."'";
    // prepare query statement
    $stmt = $this->conn->prepare($query);
    // sanitize
    $this->email=htmlspecialchars(strip_tags($this->email));
    $this->password=htmlspecialchars(strip_tags($this->password));
    // bind values
    $stmt->bindParam(":email", $this->email);
    $stmt->bindParam(":password", $this->password);
    // execute query
    $stmt->execute();
    return $stmt;
    }
    
    // a function to check if email already exists
    function isAlreadyExist(){
    $query = "SELECT *
        FROM
            " . $this->table_name . " 
        WHERE
            username='".$this->email."'";
    // prepare query statement
    $stmt = $this->conn->prepare($query);
    // sanitize
    $this->email=htmlspecialchars(strip_tags($this->email));
    // bind values
    $stmt->bindParam(":email", $this->email);
    // execute query
    $stmt->execute();
    if($stmt->rowCount() > 0){
        return true;
    }
    else{
        return false;
    }
    }
}