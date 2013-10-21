<?php
require_once('../bys-provider.php');

/**
 * CREATE PROVIDER
 */

// set acces token's client
$access_token = "JVhkkQslUtmrZM3pdvt4DVMWfM6BcSho";

// set provider values
$name                          = "New provider";
$ico                           = "12345678";
$dic                           = "0123456789";
$icdph                         = "SK0123456789";
$street                        = "Fake 3";
$city                          = "Bratislava";
$zip                           = "12345";
$province                      = "Bratislvský kraj";
$state                         = "Slovensko";
$timezoneId                    = "Europe/Berlin";
$defaultLanguage               = "SK";
$description                   = "description";
$phone                         = "+0123456";
$email                         = "example@example.org";
$web                           = "bookyourself.com";
$timeBeforeReservation         = 5600;
$createReservationNotification = true;
$updateReservationNotification = true;
$deleteReservationNotification = true;
$scope                         = "provider_logged_iframes provider_profile provider_manage_reservations";


$bysProvider = new BysProvider();
// call method "createProvider" which return save values
$newProvider = $bysProvider->createProvider($access_token, $name, $ico, $dic, $icdph, $street, $city, $zip, $province, $state, $timezoneId, $defaultLanguage, $description, $phone, $email, $web, $timeBeforeReservation, $createReservationNotification, $updateReservationNotification, $deleteReservationNotification, $scope);

if ($newProvider[status] == true) {
    
    echo 'Provider id: ';
    echo $newProvider[id];
    echo '<br /> Create At: ';
    echo $newProvider[createdAt];
    echo '<br /> Updated At: ';
    echo $newProvider[updatedAt];
    echo '<br />Name: ';
    echo $newProvider[name];
    echo '<br />ico: ';
    echo $newProvider[ico];
    echo '<br />dic: ';
    echo $newProvider[dic];
    echo '<br />icdph: ';
    echo $newProvider[icdph];
    echo '<br />Street: ';
    echo $newProvider[street];
    echo '<br />City: ';
    echo $newProvider[city];
    echo '<br />ZIP: ';
    echo $newProvider[zip];
    echo '<br />Province: ';
    echo $newProvider[province];
    echo '<br />State: ';
    echo $newProvider[state];
    echo '<br />TimezoneId: ';
    echo $newProvider[timezoneId];
    echo '<br />DefaultLanguage: ';
    echo $newProvider[defaultLanguage];
    echo '<br />Description: ';
    echo $newProvider[description];
    echo '<br />Phone: ';
    echo $newProvider[phone];
    echo '<br />Email: ';
    echo $newProvider[email];
    echo '<br />WEB: ';
    echo $newProvider[web];
    echo '<br />UserId: ';
    echo $newProvider[userId];
    echo '<br />Time before reservation: ';
    echo $newProvider[timeBeforeReservation];
    echo '<br />Create reservation notifikation: ';
    echo $newProvider[createReservationNotification];
    echo '<br />Update reservation notifikation: ';
    echo $newProvider[updateReservationNotification];
    echo '<br />Delete reservation notifikation: ';
    echo $newProvider[deleteReservationNotification];
    echo '<br />Acces token: ';
    echo $newProvider[acces_token];
    echo '<br />Refresh token:';
    echo $newProvider[refresh_token];
    echo '<br />Expire_in: ';
    echo $newProvider[expire_in];
}

else {
    echo error;
}
?>
