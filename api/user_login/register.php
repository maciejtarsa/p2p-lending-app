<?php

// structure copied from
// https://codinginfinite.com/restful-web-services-php-example-php-mysql-source-code/
 
// get database connection
include_once '../config/database.php';
 
// instantiate user object
include_once '../objects/user_login.php';
 
$database = new Database();
$db = $database->getConnection();
 
$user = new User($db);
 
// set user property values
$user->email = $_POST['email'];
$user->password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$user->name = $_POST['name'];
$user->address1 = $_POST['address1'];
$user->address2 = $_POST['address2'];
$user->town = $_POST['town'];
$user->postcode = $_POST['postcode'];
 
// create the user
if($user->signup()){
    $user_arr=array(
        "status" => true,
        "message" => "Successfully registered!",
    );
}
else{
    $user_arr=array(
        "status" => false,
        "message" => "Username already exists!"
    );
}
print_r(json_encode($user_arr));
?>