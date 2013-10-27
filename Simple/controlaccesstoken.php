<?php
// Simple url:
// http://www.example.org/Simple/ccontrolaccesstoken.php?access_token=D4lIOmUX7c3jK6SyF4rhZEQtWe0bJ6jv

require_once("./config.php");
require_once("../bys-client.php");

session_start();

// First determine if access token is set and is not NULL.
// If no, throws an exception.
try {
    if (empty($_GET['access_token'])) {
		// If access token is not set and is not NULL throws an exception.
        throw new exception('Access token is not set.');
    } {
        $client  = new BysClient;
        // Call method "controlAccessToken" with Access token
        $control = $client->controlAccessToken($_GET['access_token']);
        
        // Determine if Status Code is 200. If no, throws an exception.
        // If ok, shown get value.
        try {
            if ($control['status'] != 200) {
                throw new exception('Status Code is not 200. You are probably using an old access token.');
            } {
                echo 'Status Code:';
                echo $control['status'];
                echo '<br />ID:';
                echo $control['id'];
                echo '<br />User ID:';
                echo $control['userId'];
                echo '<br />Provider ID:';
                echo $control['providerId'];
                echo '<br />App ID:';
                echo $control['appId'];
                echo '<br />expire time:';
                echo $control['expire'];
                echo '<br />Type:';
                echo $control['type'];
            }
        }
        catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }
}
catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}

?>
