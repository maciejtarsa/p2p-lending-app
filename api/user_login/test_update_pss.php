<?php

/*

The below JavaScript function will update the pss and encrypt this
I used it to change the password for users I created before I implemented
encryption.

function changePassword() {
    
    //create variables for each of the elements to be posted
    var email = "mike_a@mail.com";
    var password = "123";
    

    // Then post them to the database
    $.post( "http://mtarsa.heliohost.org/api/user_login/test_update_pss.php",
    {
    email: email, password: password
    },
    function(data, status) {
        var response = JSON.parse(data);
        if (response.status == true) {
            // and return to the menu
            //clearNonMenu();
            alert(response.email);
            alert(response.password);
        }
        else{
            // alert the error
            alert(response.message);
        }
    });
}*/


// include database and object files
include_once '../config/database.php';
include_once '../objects/user_login.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare user object
$user = new User($db);
// set user property values
$user->email = $_POST['email'];
$user->password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    // update user details
    if($user->update_pss()){
        $response_arr=array(
            "status" => true,
            "message" => "Successfully Updated!",
            "email" => $user->email,
            "password" => $user->password
        );
    }
    else{
        $response_arr=array(
            "status" => false,
            "message" => "Update failed!"
        );
    }

// make it json format
print_r(json_encode($response_arr));
?>

