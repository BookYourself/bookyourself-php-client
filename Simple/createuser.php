<?php
require_once('../bys-client.php');
require_once('../bys-user.php');

session_start();

/*
 * CREATE USER
 */

// Set value new user.
$firstName     = "Ján";
$lastName      = "Novák";
$email         = "m01j2j750@testbys.eu";
$password      = "123456";
$passwordAgain = "123456";
$phone         = "+12345678";
$activated     = true;

// Create the instance BysClient.
$client = new BysClient();
$bysUser = new BysUser($firstName, $lastName, $email, $password, $passwordAgain, $phone, $activated);

// Call method "createUser" which return set value new user.
$newUser = $bysUser->create();
try {
    if ($newUser['status'] != 200) {
        throw new Exception('Status Code is not 200.');
    } {
        if ($newUser['isNew'] == true) {
            
            // Shown return value user.
            echo "New user created:<br/>";
            echo "User ID: ";
            echo $newUser['id'];
            print "<br />Created At: ";
            echo $newUser['createdAt'];
            print "<br />Updated At: ";
            echo $newUser['updatedAt'];
            print "<br />Email: ";
            echo $newUser['email'];
            print "<br />First name: ";
            echo $newUser['firstName'];
            print "<br />Last name: ";
            echo $newUser['lastName'];
            print "<br />Phone: ";
            echo $newUser['phone'];
            print "<br />Activated: ";
            echo $newUser['activated'];
            print "<br />Is new? ";
            echo $newUser['isNew'];
            
            
            // Set scope new user.
            $scope = "logged_iframes+profile+manage_reservations";
            
            // Call method "addScope" which return autentification code, which exchange for tokens.
            $getCode = $client->addScope($scope, $email, $password);
            
            // Call method "getTokens" with code what parameter. It's return tokens and expire time.
            $tokens = $client->getTokens($getCode['id']);
            echo '<br /> access token: ';
            echo $tokens['access_token'];
            echo '<br /> expires in: ';
            echo $tokens['expires_in'];
            echo '<br />refresh token: ';
            echo $tokens['refresh_token'];
            
        }
        
        else {
            // Shown return value user.
            echo "New user created:<br/>";
            echo "User ID: ";
            echo $newUser['id'];
            print "<br />Created At: ";
            echo $newUser['createdAt'];
            print "<br />Updated At: ";
            echo $newUser['updatedAt'];
            print "<br />Email: ";
            echo $newUser['email'];
            print "<br />First name: ";
            echo $newUser['firstName'];
            print "<br />Last name: ";
            echo $newUser['lastName'];
            print "<br />Phone: ";
            echo $newUser['phone'];
            print "<br />Activated: ";
            echo $newUser['activated'];
            print "<br />Is new? ";
            echo $newUser['isNew'];
            
            
            /*
             * GET TOKEN OF SESSION
             * 
             * This part must be placed by set "redirect URL".
             */
            
            // First determine if session CODE is set and is not NULL. 
            if (isset($_GET['code'])) {
                // Call method "getTokens" with code what parameter. It's return tokens and expire time.
                $tokens = $client->getTokens($_GET['code']);
                // Control if Status Code did ok.
                if ($tokens['status'] == true) {
                    // Shown obtained values.
                    echo 'access token: ';
                    echo $tokens['access_token'];
                    echo '<br /> expires in: ';
                    echo $tokens['expires_in'];
                    echo '<br />refresh token: ';
                    echo $tokens['refresh_token'];
                }
            }
            
            // If session CODE is not set.
            else {
                
                /**
                 * CREATE AUTH URL
                 */
                
                // Set values
                $scope         = "logged_iframes+profile+manage_reservations";
                $state         = "code";
                $access_type   = "offline";
                $response_type = "code";
                $provider_id   = null;
                $as_json       = "false";
                
                // Call method "createAuthUrl" which return authentificate URL
                $redirect_uri = $client->createAuthUrl($scope, $state, $access_type, $response_type, $provider_id, $as_json);
                
                // Create link with Auth URL
                echo '<br />This user is already registered. Please click on the following link, which will link the accounts: <br />';
                print "<a class='login' href='" . $redirect_uri . "'>Connect Me!</a>";
            }
        }
    }
}
catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
?>
