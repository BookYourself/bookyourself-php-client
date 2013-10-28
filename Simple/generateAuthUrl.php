<?php

require_once '../bys-client.php';

session_start();

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
    try {
        if ($tokens['status'] != 200) {
            throw new Exception('Status Code is not 200.');
        } {
            // Shown obtained values.
            echo 'access token: ';
            echo $tokens['access_token'];
            echo '<br /> expires in: ';
            echo $tokens['expires_in'];
            echo '<br />refresh token: ';
            echo $tokens['refresh_token'];
        }
    }
    catch (Exception $e) {
        echo 'Error: ' . $e->getMessage();
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
    print "<a class='login' href='" . $redirect_uri . "'>Connect Me!</a>";
}

?>
