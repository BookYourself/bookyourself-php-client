<?php
// Simple URL:
// http://www.example.org/Simple/iframes.php?providerId=2054&accessToken=5IhGNvbuMFelppWz0EkFdW8AacGPSKg0&refreshToken=k0GSdkVqc1zBolWAtJI4VbynvoQfpF6C

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


// First determine if accessToken and provideId are set and aren't NULL. 
try {
    if (empty($_GET['accessToken']) || empty($_GET['providerId'])) {
        throw new Exception('Access token or Provider ID is null.');
    } {
		
		$expireTime = $client->controlAccessToken($_GET['accessToken']);

        // Next confirm validity access Token throw Expire time.    
        $date       = new DateTime();
        $actualTime = $date->getTimestamp();
        if ($expireTime[expire] / 1000 > $actualTime) {
            
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
                try {
                    // Validate if change was conducted ok
                    if ($newAccessToken['status'] != 200) {
                        throw new Exception('Status Code is not 200.');
                    } {
                        // set new access_token to variable
                        $access = $newAccessToken['access_token'];
                        
                        /*
                         * Call method createIframe
                         */
                        $iframCal  = $client->createIframe($typeCal, $provider_id, $access, $width, $height);
                        $iframEdit = $client->createIframe($typeEdit, $provider_id, $access, $width, $height);
                        
                        // generating Iframes
                        echo "$iframCal";
                        echo "<br />";
                        echo "$iframEdit";
                        
                    }
                }
                catch (Exception $e) {
                    echo 'Error: ' . $e->getMessage();
                }
            }
            
            else {
                
                echo 'No validate token.';
                
            }
        }
    }
}

catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}

?>
