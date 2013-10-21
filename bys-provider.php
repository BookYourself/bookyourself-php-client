<?php
require_once("httpful.phar");
require_once("config.php");
require_once("bys-client.php");

class BysProvider
{
    
    /*
     * CREATE PROVIDER
     */
    public function createProvider($access_token, $name, $ico, $dic, $icdph, $street, $city, $zip, $province, $state, $timezoneId, $defaultLanguage, $description, $phone, $email, $web, $timeBeforeReservation, $createReservationNotification, $updateReservationNotification, $deleteReservationNotification, $scope)
    {
        global $client_id;
        global $client_secret;
        $part     = "providers.json";
        $client   = new BysClient();
        $url      = $client->setEnviromentURL() . "$part";
        $jsonData = array(
            'name' => $name,
            'ico' => $ico,
            'dic' => $dic,
            'icdph' => $icdph,
            'street' => $street,
            'city' => $city,
            'zip' => $zip,
            'province' => $province,
            'state' => $state,
            'timezoneId' => $timezoneId,
            'defaultLanguage' => $defaultLanguage,
            'description' => $description,
            'phone' => $phone,
            'email' => $email,
            'web' => $web,
            'timeBeforeReservation' => $timeBeforeReservation,
            'createReservationNotification' => $createReservationNotification,
            'updateReservationNotification' => $updateReservationNotification,
            'deleteReservationNotification' => $deleteReservationNotification,
            'scope' => $scope
        );
        $json     = json_encode($jsonData);
        
        $response = \Httpful\Request::post($url)->AddHeaders(array(
            'Cache-Control' => 'no-cache',
            'client_id' => $client_id,
            'client_secret' => $client_secret,
            'access_token' => $access_token
        ))->contentType("application/json")->body("$json")->send();
        
        $status = $response->code;
        if ($status == 200) {
            
            $createProvider = array(
                status => true,
                id => $response->body->id,
                createdAt => $response->body->createdAt,
                updatedAt => $response->body->updatedAt,
                name => $response->body->name,
                ico => $response->body->ico,
                dic => $response->body->dic,
                icdph => $response->body->icdph,
                street => $response->body->street,
                city => $response->body->city,
                zip => $response->body->zip,
                province => $response->body->province,
                state => $response->body->state,
                timezoneId => $response->body->timezoneId,
                timezoneOffset => $response->body->timezoneOffset,
                summerTimeOffset => $response->body->summerTimeOffset,
                defaultLanguage => $response->body->defaultLanguage,
                description => $response->body->description,
                phone => $response->body->phone,
                email => $response->body->email,
                web => $response->body->web,
                userId => $response->body->userId,
                timeBeforeReservation => $response->body->timeBeforeReservation,
                createReservationNotification => $response->body->createReservationNotification,
                updateReservationNotification => $response->body->updateReservationNotification,
                deleteReservationNotification => $response->body->deleteReservationNotification,
                acces_token => $response->body->access_token,
                refresh_token => $response->body->refresh_token,
                expire_in => $response->body->expires_in
                
            );
            
        } else {
            $createProvider = array(
                status => false
            );
        }
        
        return $createProvider;
        
    }
    
    /*
     * DELETE PROVIDER
     */
    public function deleteProvider($id_provider, $access_token)
    {
        global $client_id;
        global $client_secret;
        $oauth  = "providers/$id_provider.json";
        $client = new BysClient();
        $url    = $client->setEnviromentURL() . "$oauth";
        
        $response = \Httpful\Request::delete($url)->AddHeaders(array(
            'client_id' => $client_id,
            'client_secret' => $client_secret,
            'access_token' => $access_token
        ))->send();
        
        $status = $response->code;
        if ($status == 200) {
            $return = true;
        } else {
            $return = false;
        }
        return $return;
        
    }
    
}

?>
