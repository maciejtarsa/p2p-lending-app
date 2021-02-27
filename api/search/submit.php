<?php
// include database and object files
include_once '../config/database.php';
include_once '../objects/search.php';

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
$search = new Search($db);

$search->game_id = $_POST['game_id'];
$search->start_date = date("Y-m-d",strtotime(strtr($_POST['startDate'], '/', '-')));
//$search->start_date = date("Y-m-d",strtotime($search->start_date));
$search->duration = $_POST['duration'];
$search->comments = $_POST['comments'];
$jwt_value = $_POST['stored_jwt'];


// if jwt is not empty
if($jwt_value) {
    // decode jwt
    $decoded = JWT::decode($jwt_value, $key, array('HS256'));
    // turn it into an array
    $decoded_array = (array) $decoded;
    // and extract the email to use fo the query
    $search->email = $decoded_array['email'];

    // create a new game
    $response = $search->addRequest();
    if($response != false){
        $response_arr=array(
            "status" => true,
            "loan_id" => $response,
            "message" => "The request has been sent to the owner."
        );
    }
    else{
        $response_arr=array(
            "status" => false,
            "message" => "The new request has NOT been sent",
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

