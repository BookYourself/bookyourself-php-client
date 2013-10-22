<?php
// Simple URL:
// http://www.example.org/Simple/iframy.php?providerId=1749&accessToken=zqMmkcE2u6j71142bkWP4EnHLPpZhOPe&refreshToken=jBetgspEHnE7BidHWqa8vers56DEETpF&accessExpire=2282093757

require_once '../bys-client.php';

session_start();

$client = new BysClient();

//set TYPE iframes
$typeCal  = "provider_calendar";
$typeEdit = "provider_edit";

// get providerId and accessToken of session
$provider_id  = $_GET['providerId'];
$access_token = $_GET['accessToken'];

// set width and height iframes
$width  = 1000;
$height = 1000;


// First determine if session accessExpire, accessToken and provideId are set and aren't NULL. 
if (isset($_GET['accessExpire']) && isset($_GET['accessToken']) && $_GET['providerId']) {
    // Next confirm validity access Token throw Expire time.    
    $date       = new DateTime();
    $actualTime = $date->getTimestamp();
    if ($_GET['accessExpire'] >= $actualTime) {
        
        /*
         * Call method createIframe
         */
        $iframCal  = $client->createIframe($typeCal, $provider_id, $access_token, $width, $height);
        $iframEdit = $client->createIframe($typeEdit, $provider_id, $access_token, $width, $height);
        
        // generating Iframes
        echo "$iframCal";
        echo "<br />";
        echo "$iframEdit";
    }
    // if don't have got validate access token
    else {
        // First determine if refresh token is set and is not NULL. 
        if (isset($_GET['refreshToken'])) {
            // call method changeTokens, help which change refresh token for access token
            $newAccessToken = $client->changeTokens($_GET['refreshToken']);
            // Validate if change was conducted ok
            if ($newAccessToken[status] == true) {
                // set new access_token to variable
                $access = $newAccessToken[access_token];
                
                /*
                 * Call method createIframe
                 */
                $iframCal  = $client->createIframe($typeCal, $provider_id, $access, $width, $height);
                $iframEdit = $client->createIframe($typeEdit, $provider_id, $access, $width, $height);
                
                // generating Iframes
                echo "$iframCal";
                echo "<br />";
                echo "$iframEdit";
                
            } else {
                echo error;
            }
        }
        
        else {
            
            echo 'No validate token.';
            
        }
    }
}

else {
    echo error;
}

?>
