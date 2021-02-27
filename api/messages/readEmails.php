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
//$messages->other_email = $_GET['other_email'];

// if jwt is not empty
if($jwt_value) {
    // decode jwt
    $decoded = JWT::decode($jwt_value, $key, array('HS256'));
    // turn it into an array
    $decoded_array = (array) $decoded;
    // and extract the email to use fo the query
    $messages->email = $decoded_array['email'];
    
    // read the details of the messages
    $stmt = $messages->readEmailsReceiver();
    if($stmt->rowCount() > 0){
        // create an array for the users
        $response_arr = array(
            "status" => true);
        $response_arr["user"] = array();
        // get retrieved rows
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $user_item=array(
            "messages_email" => $messages_email,
            "name" => $name,
            "unread" => $unread,
            "timestamp" => $timestamp
        );
        array_push($response_arr["user"], $user_item);
        }
        // create an array of emails in response so far
        $superusers = array();
        foreach ($response_arr["user"] as $users) {
            array_push($superusers, $users['messages_email']);
        }
    }
    // read the details of the messages
    $stmt = $messages->readEmailsSender();
    if($stmt->rowCount() > 0){
        // get retrieved rows
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
            if(isset($superusers) == false) {
                // create an array for the users
                $response_arr = array(
                "status" => true);
                $response_arr["user"] = array();
                $user_item=array(
                    "messages_email" => $messages_email,
                    "name" => $name,
                    "unread" => "0",
                    "timestamp" => $timestamp
                );
                array_push($response_arr["user"], $user_item);
            }
            elseif (in_array($messages_email, $superusers) == false) {
                $user_item=array(
                    "messages_email" => $messages_email,
                    "name" => $name,
                    "unread" => "0",
                    "timestamp" => $timestamp
                );
            array_push($response_arr["user"], $user_item);
            }
        }
        // ideally sort the array here 
        $timestamp = array_column($response_arr["user"], 'timestamp');
        array_multisort($timestamp, SORT_DESC, $response_arr["user"]);
    }
    if (isset($response_arr) == false){
        $response_arr=array(
            "status" => false,
            "message" => "No messages found!",
        );
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

