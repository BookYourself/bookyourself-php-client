<?php
require_once '../bys-user.php';
require_once '../bys-client.php';

session_start();

/*
 * CREATE USER
 */

// Set value new user.
$firstName     = "Ján";
$lastName      = "Novák";
$email         = "m01060@testbys.eu";
$password      = "123456";
$passwordAgain = "123456";
$phone         = "+123456";
$activated     = true;

$bysUser = new BysUser($firstName, $lastName, $email, $password, $passwordAgain, $phone, $activated);
// Call method "create" which return set value new user.
$newUser = $bysUser->create();

if ($newUser[status] == true) {
    
    if ($newUser[isNew] == true) {
        
        // Shown return value user.
        echo "New user created:<br/>";
        echo "User ID: ";
        print "$newUser[id]<br />Created At: ";
        print "$newUser[createdAt]<br />Updated At: ";
        print "$newUser[updatedAt]<br />Email: ";
        print "$newUser[email]<br />First name: ";
        print "$newUser[firstName]<br />Last name: ";
        print "$newUser[lastName]<br />Phone: ";
        print "$newUser[phone]<br />Activated: ";
        print "$newUser[activated]<br />Is new? ";
        print "$newUser[isNew]";
        
        // Set scope new user.
        $scope = "logged_iframes+profile+manage_reservations";
        
        // Call method "addScope" which return autentification code, which exchange for tokens.
        $getCode = $bysUser->addScope($scope, $email, $password);
        
        $client = new BysClient();
        // Call method "getTokens" with code what parameter. It's return tokens and expire time.
        $tokens = $client->getTokens($getCode[id]);
        echo '<br /> access token: ';
        echo $tokens[access_token];
        echo '<br /> expires in: ';
        echo $tokens[expires_in];
        echo '<br />refresh token: ';
        echo $tokens[refresh_token];
        
    }
    
    else {
		        // Shown return value user.
        echo "New user created:<br/>";
        echo "User ID: ";
        print "$newUser[id]<br />Created At: ";
        print "$newUser[createdAt]<br />Updated At: ";
        print "$newUser[updatedAt]<br />Email: ";
        print "$newUser[email]<br />First name: ";
        print "$newUser[firstName]<br />Last name: ";
        print "$newUser[lastName]<br />Phone: ";
        print "$newUser[phone]<br />Activated: ";
        print "$newUser[activated]<br />Is new? ";
        print "$newUser[isNew]";
        
        $client = new BysClient();
        
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
            if ($tokens[status] == true) {
                // Shown obtained values.
                echo 'access token: ';
                echo $tokens[access_token];
                echo '<br /> expires in: ';
                echo $tokens[expires_in];
                echo '<br />refresh token: ';
                echo $tokens[refresh_token];
            } else {
                echo error;
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
            print "<a class='login' href='$redirect_uri'>Connect Me!</a>";
        }
        
    }
}

else {
    echo error;
}

?>
