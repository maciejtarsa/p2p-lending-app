<?php
// include database and object files
include_once '../config/database.php';
include_once '../objects/messages.php';

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
$messages = new Messages($db);

$jwt_value = $_POST['stored_jwt'];
$messages->id = $_POST['id'];

// if jwt is not empty
if($jwt_value) {
    // decode jwt
    $decoded = JWT::decode($jwt_value, $key, array('HS256'));
    // turn it into an array
    $decoded_array = (array) $decoded;
    // and extract the email to use fo the query
    $messages->email = $decoded_array['email'];
    
    // post message
    if($messages->markAsRead()){
    $response_arr=array(
        "status" => true,
        "message" => "Messages marked as read!"
        //,
        //"reposense" => $messages->markAsRead()
    );
    }
    else{
        $response_arr=array(
            "status" => false,
            "message" => "Messages NOT marked as read!"
            //,
            //"reposense" => $messages->markAsRead()
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