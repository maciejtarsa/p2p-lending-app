<?php

class Search{
 
    // database connection and table name
    private $conn;
    private $table_name1 = "game";
    private $table_name2 = "user_details";
    private $table_name3 = "loan";
    private $table_name4 = "feedback";
 
    // object properties
    public $game_id;
    public $name;
    public $description;
    public $value;
    public $email;
    public $status;
    public $postcode;
    public $stars;
    public $owner_email;
    public $some_email;
    public $feedback;
    public $duration;
    public $start_date;
    
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
    
    // read current user's postcode details
    function readPostcode(){
    // select all query
    $query = "SELECT
                `postcode`
            FROM
                " . $this->table_name2 . " 
            WHERE
                email='".$this->email."'";
    // prepare query statement
    $stmt = $this->conn->prepare($query);
    // sanitize
    $this->email=htmlspecialchars(strip_tags($this->email));
    $this->postcode=htmlspecialchars(strip_tags($this->postcode));
    // bind values
    $stmt->bindParam(":email", $this->email);
    $stmt->bindParam(":postcode", $this->postcode);    
    // execute query
    $stmt->execute();
    return $stmt;
    }
    
    // get a list of games with the postcodes of their location
    // only shows game that have been set by their owners as visible
    // and are not on loan to someone else atm
    function readGamesWithLocation(){
    // select query
    $query = 
        "SELECT g.game_id, g.name AS gname, g.description, g.email AS owner_email, u.name AS uname, u.postcode
        FROM " . $this->table_name1 . " AS g
        INNER JOIN " . $this->table_name2 . " AS u ON g.email = u.email 
        LEFT JOIN " . $this->table_name3 . " AS l ON g.game_id = l.game_id
        WHERE g.status = 'visible' AND (l.status <> 'on_loan' OR l.status IS NULL)
            AND g.email <> '".$this->email."'
        ";
    
    // prepare query statement
    $stmt = $this->conn->prepare($query);
    // sanitize
    $this->game_id=htmlspecialchars(strip_tags($this->game_id));
    $this->name=htmlspecialchars(strip_tags($this->name));
    $this->description=htmlspecialchars(strip_tags($this->description));
    $this->owner_email=htmlspecialchars(strip_tags($this->owner_email));
    $this->postcode=htmlspecialchars(strip_tags($this->postcode)); 
    // bind values
    $stmt->bindParam(":game_id", $this->game_id);
    $stmt->bindParam(":name", $this->name);
    $stmt->bindParam(":description", $this->description);
    $stmt->bindParam(":owner_email", $this->owner_email);
    $stmt->bindParam(":postcode", $this->postcode);
    // execute query
    $stmt->execute();
    return $stmt;
    }
    
    // read user's average feedback
    function readFeedback($some_email){
    // select all query
    $query = "SELECT
                AVG(`stars`) AS feedback
            FROM
                " . $this->table_name4 . " 
            WHERE
                feedbackee='".$some_email."'
                ";
    // prepare query statement
    $stmt = $this->conn->prepare($query);
    // sanitize
    $this->some_email=htmlspecialchars(strip_tags($this->some_email));
    $this->feedback=htmlspecialchars(strip_tags($this->feedback));
    // bind values
    $stmt->bindParam(":some_email", $this->some_email);
    $stmt->bindParam(":feedback", $this->feedback);    
    // execute query
    $stmt->execute();
    return $stmt;
    }
    
    // add a new game rental request
    function addRequest() {
    $query = "INSERT INTO
                " . $this->table_name3 . "
            SET
                game_id='".$this->game_id."', borrower='".$this->email."', start_date='".$this->start_date."', comments='".$this->comments."', duration='".$this->duration."', status='requested'";
    // prepare query
    $stmt = $this->conn->prepare($query);
    // sanitize
    $this->game_id=htmlspecialchars(strip_tags($this->game_id));
    $this->email=htmlspecialchars(strip_tags($this->email));
    $this->start_date=htmlspecialchars(strip_tags($this->start_date));
    $this->duration=htmlspecialchars(strip_tags($this->duration));
    $this->comments=htmlspecialchars(strip_tags($this->comments));
    // bind values
    $stmt->bindParam(":game_id", $this->game_id);
    $stmt->bindParam(":borrower", $this->email);
    $stmt->bindParam(":start_date", $this->start_date);
    $stmt->bindParam(":duration", $this->duration);
    $stmt->bindParam(":comments", $this->comments);
    // execute query
    if($stmt->execute()){
        $loan_id = $this->conn->lastInsertId();
        return $loan_id;
    }
    return false;
    }
    
    
    
}