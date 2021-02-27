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

// create a variable for Google Maps API
$MapsAPIKey = 'AIzaSyBxsXbnMcqoHDXz2CuDsooOWA_ZXntrqbk';

// a function that removes whitespaces from postcodes
function nowhitespace($data) {
    return preg_replace('/\s/', '', $data);
}
 
// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare new game object
$search = new Search($db);

$jwt_value = $_GET['stored_jwt'];

// if jwt is not empty
if($jwt_value) {
    // decode jwt
    $decoded = JWT::decode($jwt_value, $key, array('HS256'));
    // turn it into an array
    $decoded_array = (array) $decoded;
    // and extract the email to use fo the query
    $search->email = $decoded_array['email'];
    
    // 2 things need to happen here
    // first, het the postcode of the current user
    $stmt = $search->readPostcode();
    if($stmt->rowCount() > 0){
        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        // create a variable for current user's postcode
        $user_postcode=$row['postcode'];
    }

    // then get all the games available and their postcodes
    $stmt = $search->readGamesWithLocation();
    if($stmt->rowCount() > 0){
        // create an array for the games
        $response_arr = array(
            "status" => true);
        $response_arr["game"] = array();
        // get retrieved rows
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        
        // remove white spaces from the postcodes
        $postcode1 = nowhitespace($user_postcode);
        $postcode2 = nowhitespace($postcode);
        // check the distance HERE
        $distanceResult = array();
        $url = "https://maps.googleapis.com/maps/api/distancematrix/json?units=imperial&origins=".
            $postcode1."&destinations=".$postcode2."&mode=walking&key=".$MapsAPIKey;
        $data = @file_get_contents($url);
        $distanceResult = json_decode($data, true);
        $distanceKM = $distanceResult["rows"][0]["elements"][0]["distance"]["value"];
        $distance = number_format(($distanceKM * 0.000621371),2);
        
        $game_item=array(
            "game_id" => $game_id,
            "name" => $gname,
            "description" => $description,
            "owner" => $uname,
            "email" => $owner_email,
            "postcode" => $postcode,
            "distance" => $distance
        );
        array_push($response_arr["game"], $game_item);
        }
        
        for($i = 0; $i < count($response_arr["game"]); $i++) {
            $stmt = $search->readFeedback($response_arr["game"][$i]["email"]);
                if($stmt->rowCount() > 0){
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                    extract($row);
                    if($feedback==NULL) {
                        $user_feedback = 'no ratings yet';
                    }
                    else {
                    $user_feedback = number_format($feedback, 2).' out of 5';
                    }
                }
                }
            $response_arr["game"][$i]["feedback"] = $user_feedback;
        }
        
        // sort the response array based on distance and name
        // obtains a list of columns
        $distance_name = array_column($response_arr["game"], 'distance');
        $game_name = array_column($response_arr["game"], 'name');
        // sort the data with distance ascending
        array_multisort($distance_name, SORT_ASC, $game_name, SORT_ASC, $response_arr["game"]);

    }
    else{
        $response_arr=array(
            "status" => false,
            "message" => "No games found!",
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

