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

$game->game_id = $_POST['game_id'];
$game->name = $_POST['name'];
$game->description = $_POST['description'];
$game->value = $_POST['value'];
$game->status = $_POST['status'];
$jwt_value = $_POST['stored_jwt'];


// if jwt is not empty
if($jwt_value) {
    // decode jwt
    $decoded = JWT::decode($jwt_value, $key, array('HS256'));
    // turn it into an array
    $decoded_array = (array) $decoded;
    // and extract the email to use fo the query
    $game->email = $decoded_array['email'];

    // update current game
    if($game->updateGame()){
        $response_arr=array(
            "status" => true,
            "game_id" => $game->game_id,
            "message" => "The game has been updated!"
        );
    }
    else{
        $response_arr=array(
            "status" => false,
            "message" => "Changes NOT saved!",
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

