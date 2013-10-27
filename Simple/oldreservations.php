<?php
// Simple url:
// http://www.example.org/Simple/oldreservations.php?access_token=D4lIOmUX7c3jK6SyF4rhZEQtWe0bJ6jv

require_once('./config.php');
require_once('../bys-client.php');

$client = new BysClient();

// Set values.
$user_id      = 2481;
$provider_id  = 2047;
$startsBefore = 1382900254;

try {
    if (empty($_GET['access_token'])) {
        // If access token is not set and is not NULL throws an exception.
        throw new exception('Access token is not set.');
    } {
        
        // Call method "oldReservations" which return COUNT old reservations.
        $old = $client->oldReservations($user_id, $provider_id, $startsBefore, $_GET['access_token']);
        
        try {
            if ($old['status'] != 200) {
                throw new Exception('Status Code is not 200.');
            } {
                $count = $old['count'];
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
// Return count old reservations.
print $count;

?>
