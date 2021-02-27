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

$jwt_value = $_GET['stored_jwt'];

// if jwt is not empty
if($jwt_value) {
    // decode jwt
    $decoded = JWT::decode($jwt_value, $key, array('HS256'));
    // turn it into an array
    $decoded_array = (array) $decoded;
    // and extract the email to use fo the query
    $messages->email = $decoded_array['email'];
    
    // read the details of the messages
    $stmt = $messages->unreadMessages();
    if($stmt->rowCount() > 0){
        // create an array for the users
        $response_arr = array(
            "status" => true);
        // get retrieved rows
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $response_arr["unread"] = $unread;
    }
    if (isset($response_arr) == false){
        $response_arr=array(
            "status" => false,
            "message" => "No messages found!",
        );
    }
    }
}
    
else{
    $response_arr=array(
        "status" => false,
        "message" => "Authorisation failed!",
        );
}
// make it json format
print_r(json_encode($response_arr));
?>

