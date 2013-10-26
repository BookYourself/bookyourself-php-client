<?php
require_once('./config.php');
require_once('../bys-client.php');

$client  = new BysClient();

// Set values.
$access_token = 'BQK4eHRKIwDjstkCPtHyqU6MzkdKR8TI';
$user_id      = 2504;
$provider_id  = 2054;
$startsBefore = 1382070203;

// Call method "oldReservations" which return COUNT old reservations.
$old = $client->oldReservations($user_id, $provider_id, $startsBefore, $access_token);

// Return count old reservations.
print $old['count'];

?>
