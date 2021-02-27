<?php
// include database and object files
include_once '../config/database.php';
include_once '../objects/user_details.php';

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

// prepare user object
$user_details = new User_details($db);

// set user property values
    $user_details->name = $_POST['name'];
    $user_details->address1 = $_POST['address1'];
    $user_details->address2 = $_POST['address2'];
    $user_details->town = $_POST['town'];
    $user_details->postcode = $_POST['postcode'];
    
    $jwt_value = $_POST['stored_jwt'];
    
    // if jwt is not empty
    if($jwt_value) {
        // decode jwt
        $decoded = JWT::decode($jwt_value, $key, array('HS256'));
        // turn it into an array
        $decoded_array = (array) $decoded;
        // and extract the email to use fo the query
        $user_details->email = $decoded_array['email'];


    // update user details
    if($user_details->update()){
        $response_arr=array(
            "status" => true,
            "message" => "Successfully Updated!"
        );
    }
    else{
        $response_arr=array(
            "status" => false,
            "message" => "Update failed!"
        );
    }
}
// else if auth failed, return a suitable message
else{
    $response_arr=array(
        "status" => false,
        "message" => "Authorisation failed!"
        );
}
// make it json format
print_r(json_encode($response_arr));
?>