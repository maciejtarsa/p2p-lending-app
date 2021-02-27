<?php

// structure copied from
// https://codinginfinite.com/restful-web-services-php-example-php-mysql-source-code/

// include database and object files
include_once '../config/database.php';
include_once '../objects/user_login.php';

// generate json web token
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
$user = new User($db);
// set ID property of user to be edited
$user->email = isset($_GET['email']) ? $_GET['email'] : die();
$user->password = isset($_GET['password']) ? $_GET['password'] : die();
// read the details of user to be edited
$stmt = $user->login();
// get retrieved row
$row = $stmt->fetch(PDO::FETCH_ASSOC);

// the section about generating tokens taken from
// https://www.codeofaninja.com/2018/09/rest-api-authentication-example-php-jwt-tutorial.html


 
// generate jwt
if(password_verify($user->password, $row['password'])) {
    // Login successful
    
    $token = array(
       "iss" => $iss,
       "aud" => $aud,
       "iat" => $iat,
       "nbf" => $nbf,
       "email" => $user->email
    );
    
    // set response code
    http_response_code(200);
 
    // generate jwt
    $jwt = JWT::encode($token, $key);
    
            $user_arr = array(
            "status" => true,
            "message" => "Successful login",
            "jwt" => $jwt
            );
}
else{
    
    // set response code
    //http_response_code(401);
    // tell the user login failed
    $user_arr = array(
        "status" => false,
        "message" => "invalid username or password");

    //$user_arr=array(
    //    "status" => false,
     //   "message" => "Invalid Username or Password!",
    //);
}
// make it json format
print_r(json_encode($user_arr));
?>

