<?php

// structure copied from
// https://codinginfinite.com/restful-web-services-php-example-php-mysql-source-code/

class Database{
 
    // specify your own database credentials
    private $host = "localhost";
    private $db_name = "mtarsa_board_games";
    private $username = "mtarsa_admin";
    private $password = "BoardGamesAdmin";
    public $conn;
 
    // get the database connection
    public function getConnection(){
 
        $this->conn = null;
 
        try{
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        }catch(PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }
 
        return $this->conn;
    }
}
?>