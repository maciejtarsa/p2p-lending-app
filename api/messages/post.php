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
$messages->other_email = $_POST['other_email'];
$messages->typed_message = $_POST['typed_message'];

// if jwt is not empty
if($jwt_value) {
    // decode jwt
    $decoded = JWT::decode($jwt_value, $key, array('HS256'));
    // turn it into an array
    $decoded_array = (array) $decoded;
    // and extract the email to use fo the query
    $messages->email = $decoded_array['email'];
    
    // post message
    if($messages->post()){
    $response_arr=array(
        "status" => true,
        "message" => "Message posted!"
    );
    }
    else{
        $response_arr=array(
            "status" => false,
            "message" => "Message NOT posted!",
            "1" => $messages->email,
            "2" => $messages->other_email,
            "3" => $messages->typed_message
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