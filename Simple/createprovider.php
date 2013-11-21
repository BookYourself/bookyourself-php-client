<?php
// Simple url:
// http://www.example.org/Simple/createprovider.php?refresh_token=D4lIOmUX7c3jK6SyF4rhZEQtWe0bJ6jv

require_once('../bys-provider.php');
require_once('./config.php');

session_start();

$client = new BysClient();

/**
 * CREATE PROVIDER
 */

// First determine if refresh token is set and is not NULL.
// If no, throws an exception.
try {
    if (empty($_GET['refresh_token'])) {
        throw new Exception('Refresh token value is empty.');
    } {
        // call method changeTokens, help which change refresh token for access token
        $newAccessToken = $client->changeTokens($_GET['refresh_token']);
        
        // set new access_token to variable
        $access_token = $newAccessToken['access_token'];
        
        // set provider values
        $name                          = "New Provider";
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
        $phone                         = "+01234567";
        $email                         = "example@example.org";
        $web                           = "bookyourself.com";
        $timeBeforeReservation         = 86400000;
        $createReservationNotification = true;
        $updateReservationNotification = true;
        $deleteReservationNotification = true;
        $scope                         = "provider_profile provider_manage_profile provider_manage_reservations provider_logged_iframes";
        
        $bysProvider = new BysProvider($name, $ico, $dic, $icdph, $street, $city, $zip, $province, $state, $timezoneId, $defaultLanguage, $description, $phone, $email, $web, $timeBeforeReservation, $createReservationNotification, $updateReservationNotification, $deleteReservationNotification, $scope);
        
        // call method "create" which return save values
        $newProvider = $bysProvider->create($access_token);
        
        // Determine if Status Code is 200. If no, throws an exception.
        // If ok, shown get value.        
        try {
            if ($newProvider['status'] != 200) {
                throw new Exception('Status Code is not 200.');
            } {
                echo 'Provider id: ';
                echo $newProvider['id'];
                echo '<br /> Create At: ';
                echo $newProvider['createdAt'];
                echo '<br /> Updated At: ';
                echo $newProvider['updatedAt'];
                echo '<br />Name: ';
                echo $newProvider['name'];
                echo '<br />ico: ';
                echo $newProvider['ico'];
                echo '<br />dic: ';
                echo $newProvider['dic'];
                echo '<br />icdph: ';
                echo $newProvider['icdph'];
                echo '<br />Street: ';
                echo $newProvider['street'];
                echo '<br />City: ';
                echo $newProvider['city'];
                echo '<br />ZIP: ';
                echo $newProvider['zip'];
                echo '<br />Province: ';
                echo $newProvider['province'];
                echo '<br />State: ';
                echo $newProvider['state'];
                echo '<br />TimezoneId: ';
                echo $newProvider['timezoneId'];
                echo '<br />DefaultLanguage: ';
                echo $newProvider['defaultLanguage'];
                echo '<br />Description: ';
                echo $newProvider['description'];
                echo '<br />Phone: ';
                echo $newProvider['phone'];
                echo '<br />Email: ';
                echo $newProvider['email'];
                echo '<br />WEB: ';
                echo $newProvider['web'];
                echo '<br />UserId: ';
                echo $newProvider['userId'];
                echo '<br />Time before reservation: ';
                echo $newProvider['timeBeforeReservation'];
                echo '<br />Create reservation notifikation: ';
                echo $newProvider['createReservationNotification'];
                echo '<br />Update reservation notifikation: ';
                echo $newProvider['updateReservationNotification'];
                echo '<br />Delete reservation notifikation: ';
                echo $newProvider['deleteReservationNotification'];
                echo '<br />Acces token: ';
                echo $newProvider['acces_token'];
                echo '<br />Refresh token:';
                echo $newProvider['refresh_token'];
                echo '<br />Expire_in: ';
                echo $newProvider['expire_in'];
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
