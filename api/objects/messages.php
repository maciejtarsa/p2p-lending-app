<?php

class Messages{
 
    // database connection and table name
    private $conn;
    private $table_name = "communication";
    private $table_name2 = "user_details";
    
 
    // object properties
    public $id;
    public $sender;
    public $receiver;
    public $timestamp;
    public $message;
    public $messages_email;
    public $unread;
    public $email;
    public $name;
    public $typed_message;
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
    
    // get a list of emails a user received messages from
    function readEmailsReceiver(){
    // select query
    $query = 
        "SELECT m.sender AS messages_email, u.name AS name, SUM(m.unread) AS unread, MAX(m.timestamp) AS timestamp
        FROM " . $this->table_name . " AS m
        INNER JOIN " . $this->table_name2 . " AS u ON m.sender = u.email 
        WHERE m.receiver = '".$this->email."'
        GROUP BY messages_email
        ";
      
    // prepare query statement
    $stmt = $this->conn->prepare($query);
    // sanitize
    $this->messages_email=htmlspecialchars(strip_tags($this->messages_email));
    $this->name=htmlspecialchars(strip_tags($this->name));
    $this->unread=htmlspecialchars(strip_tags($this->unread));
    $this->timestamp=htmlspecialchars(strip_tags($this->timestamp));    
    // bind values
    $stmt->bindParam(":messages_email", $this->messages_email);
    $stmt->bindParam(":name", $this->name);
    $stmt->bindParam(":unread", $this->unread);
    $stmt->bindParam(":timestamp", $this->timestamp);
    // execute query
    $stmt->execute();
    return $stmt;
    }
    
    // get a list of emails a user sent messages to
    function readEmailsSender(){
    $query = 
        "SELECT m.receiver AS messages_email, u.name AS name, m.timestamp AS timestamp
        FROM " . $this->table_name . " AS m
        INNER JOIN " . $this->table_name2 . " AS u ON m.receiver = u.email 
        WHERE m.sender = '".$this->email."'
        GROUP BY messages_email
        ";    
    
    // prepare query statement
    $stmt = $this->conn->prepare($query);
    // sanitize
    $this->messages_email=htmlspecialchars(strip_tags($this->messages_email));
    $this->name=htmlspecialchars(strip_tags($this->name));
    $this->timestamp=htmlspecialchars(strip_tags($this->timestamp)); 
    // bind values
    $stmt->bindParam(":messages_email", $this->messages_email);
    $stmt->bindParam(":name", $this->name);
    $stmt->bindParam(":timestamp", $this->timestamp);
    // execute query
    $stmt->execute();
    return $stmt;
    }
    
    function readMessagesSent() {
    $query = 
        "SELECT id, timestamp, message
        FROM " . $this->table_name . "
        WHERE sender = '".$this->email."' AND receiver = '".$this->other_email."'
        ";    
    
    // prepare query statement
    $stmt = $this->conn->prepare($query);
    // sanitize
    $this->id=htmlspecialchars(strip_tags($this->id));
    $this->timestamp=htmlspecialchars(strip_tags($this->timestamp));
    $this->message=htmlspecialchars(strip_tags($this->message));
    // bind values
    $stmt->bindParam(":id", $this->id);
    $stmt->bindParam(":timestamp", $this->timestamp);
    $stmt->bindParam(":message", $this->message);
    // execute query
    $stmt->execute();
    return $stmt;
    }

    function readMessagesReceived() {
    $query = 
        "SELECT m.id AS id, u.name AS name, m.timestamp AS timestamp, m.message AS message, m.unread AS unread
        FROM " . $this->table_name . " AS m
        INNER JOIN " . $this->table_name2 . " AS u ON m.sender = u.email
        WHERE m.receiver = '".$this->email."' AND m.sender = '".$this->other_email."'
        "; 
    
    // prepare query statement
    $stmt = $this->conn->prepare($query);
    // sanitize
    $this->id=htmlspecialchars(strip_tags($this->id));
    $this->name=htmlspecialchars(strip_tags($this->name));
    $this->timestamp=htmlspecialchars(strip_tags($this->timestamp));
    $this->message=htmlspecialchars(strip_tags($this->message));
    $this->unread=htmlspecialchars(strip_tags($this->unread));
    // bind values
    $stmt->bindParam(":id", $this->id);
    $stmt->bindParam(":name", $this->name);
    $stmt->bindParam(":timestamp", $this->timestamp);
    $stmt->bindParam(":message", $this->message);
    $stmt->bindParam(":unread", $this->unread);
    // execute query
    $stmt->execute();
    return $stmt;
    }

    // post a new message
    function post() {
        $query = "INSERT INTO
                " . $this->table_name . "
            SET
                sender='".$this->email."', receiver='".$this->other_email."', timestamp=now(), message='".$this->typed_message."', unread='1'";
        // prepare query
        $stmt = $this->conn->prepare($query);
        // sanitize
        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->other_email=htmlspecialchars(strip_tags($this->other_email));
        $this->typed_message=htmlspecialchars(strip_tags($this->typed_message));
        // bind values
        $stmt->bindParam(":sender", $this->email);
        $stmt->bindParam(":receiver", $this->other_email);
        $stmt->bindParam(":message", $this->typed_message);
        // execute query
        if($stmt->execute()){
            return true;
        }
        //return false;
        return false;
        } 
        
    function markAsRead() {
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    unread='0'
                WHERE
                    id='".$this->id."'";
        // prepare query
        $stmt = $this->conn->prepare($query);
        // sanitize
        $this->id=htmlspecialchars(strip_tags($this->id));
        // bind values
        $stmt->bindParam(":id", $this->id);
        // execute query
        if($stmt->execute()){
            return true;
        }
        //return false;
        return false;
        } 
        
    function unreadMessages() {
        $query = "SELECT SUM(unread) AS unread
        FROM " . $this->table_name . "
        WHERE receiver = '".$this->email."'
        ";
    // prepare query statement
    $stmt = $this->conn->prepare($query);
    // sanitize
    $this->unread=htmlspecialchars(strip_tags($this->unread));
    // bind values
    $stmt->bindParam(":unread", $this->unread);
    // execute query
    $stmt->execute();
    return $stmt;
    }
}
?>