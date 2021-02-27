<?php
// show error reporting
error_reporting(E_ALL);
 
// set your default time-zone
date_default_timezone_set('Europe/London');
 
// variables used for jwt
$key = "the_day_after_the_night";
$iss = "https://mtarsa.heliohost.org";
$aud = "https://mtarsa.heliohost.org";
$iat = 1356999524;
$nbf = 1357000000;
?>