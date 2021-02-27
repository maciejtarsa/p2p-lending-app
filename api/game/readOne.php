<?php
// include database and object files
include_once '../config/database.php';
include_once '../objects/game.php';

// files for generating json web token
include_once '../config/core.php';
include_once '../libs/php-jwt-master/src/BeforeValidException.php';
include_once '../libs/php-jwt-master/src/ExpiredException.php';
include_once '../libs/php-jwt-master/src/SignatureInvalidException.php';
include_once '../libs/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;
 
// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare new game object
$game = new Game($db);

$jwt_value = $_GET['stored_jwt'];
$game->game_id = $_GET['game_id'];

// if jwt is not empty
if($jwt_value) {
    // decode jwt
    $decoded = JWT::decode($jwt_value, $key, array('HS256'));
    // turn it into an array
    $decoded_array = (array) $decoded;
    // and extract the email to use fo the query
    $game->email = $decoded_array['email'];

    // read the details of the game
    $stmt = $game->readOne();
    if($stmt->rowCount() > 0){
        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        // create array
        $response_arr=array(
            "status" => true,
            "game_id" => $row['game_id'],
            "name" => $row['name'],
            "description" => $row['description'],
            "value" => $row['value'],
            "game_status" => $row['status']
        );
    }
    else{
        $response_arr=array(
            "status" => false,
            "message" => "No games found!",
        );
    }
}
// else if auth failed, return a suitable message
else{
    $response_arr=array(
        "status" => false,
        "message" => "Authorisation failed!",
        );
}
// make it json format
print_r(json_encode($response_arr));
?>

