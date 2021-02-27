<?php
// include database and object files
include_once '../config/database.php';
include_once '../objects/user_details.php';
// user_login no longer needed as I am now using token for authorisation
//include_once '../objects/user_login.php';

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

/* This section is no longer needed as I am now using token for authorisation
 First, authorise the user
 prepare user object
$user = new User($db);
// set ID property of user to be edited
$user->email = isset($_GET['email']) ? $_GET['email'] : die();
$user->password = isset($_GET['password']) ? $_GET['password'] : die();
// read the details of user to be edited
$stmt = $user->login();
// get retrieved row
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if(password_verify($user->password, $row['password'])) {
    // Login successful
    $auth = true;
}

// if authorisation is successful, carry on
if ($auth == true){
*/

    // prepare user object
    $user_details = new User_details($db);
    // set ID property of user to be edited
    //$user_details->$jwt_value = isset($_GET['stored_jwt']) ? $_GET['stored_jwt'] : die();
    $jwt_value = $_GET['stored_jwt'];
    //$user_details->password = isset($_GET['password']) ? $_GET['password'] : die();

    // if jwt is not empty
    if($jwt_value) {
        // decode jwt
        $decoded = JWT::decode($jwt_value, $key, array('HS256'));
        // turn it into an array
        $decoded_array = (array) $decoded;
        // and extract the email to use fo the query
        $user_details->email = $decoded_array['email'];

    // read the details of user
    $stmt = $user_details->readDetails();
    if($stmt->rowCount() > 0){
        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        // create array
        $response_arr=array(
            "status" => true,
            "email" => $row['email'],
            "name" => $row['name'],
            "address1" => $row['address1'],
            "address2" => $row['address2'],
            "town" => $row['town'],
            "postcode" => $row['postcode']
        );
    }
    else{
        $response_arr=array(
            "status" => false,
            "message" => "No user with that email found!",
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
