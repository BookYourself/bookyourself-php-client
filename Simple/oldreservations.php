<?php
require_once 'bys-client.php';
require_once 'bys-user.php';

$client  = new BysClient();
$bysUser = new BysUser();

// Set values.
$access_token = 'pHyrcMD0jvElsy6bCqtP5vHyZVmRkY2K';
$user_id      = 2481;
$provider_id  = 621;
$startsBefore = 1382070203;

// Call method "oldReservations" which return COUNT old reservations.
$old = $client->oldReservations($user_id, $provider_id, $startsBefore, $access_token);

// Return count old reservations.
print $old[count];

?>
