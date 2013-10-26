<?php
require_once("../httpful.phar");

class BysClient
{
    private $client_id;
    private $client_secret;
    private $redirect_uri;
    private $enviroment_url;
    
    public function __construct()
    {
        
        $a = func_get_args();
        $i = func_num_args();
        $f = '__construct1';
        
        if ($i > 0) {
            call_user_func_array(array(
                $this,
                $f
            ), $a);
        }
        
        else {
            //require_once("./config.php");
            
            try {
                global $BYS_client_id;
                global $BYS_client_secret;
                global $BYS_redirect_uri;
                global $BYS_enviroment;
                
                if ($BYS_client_id == null || $BYS_client_secret == null || $BYS_redirect_uri == null || $BYS_enviroment == null) {
                    throw new Exception('Variables aren\'t set.');
                } {
                    $bla = $this->__construct1($BYS_client_id, $BYS_client_secret, $BYS_redirect_uri, $BYS_enviroment);
                    
                }
            }
            catch (Exception $e) {
                echo 'Error: ' . $e->getMessage();
            }
            
        }
        
        
    }
    
    public function __construct1($BYS_client_id, $BYS_client_secret, $BYS_redirect_uri, $BYS_enviroment)
    {
        $this->client_id     = $BYS_client_id;
        $this->client_secret = $BYS_client_secret;
        $this->redirect_uri  = $BYS_redirect_uri;
        $this->enviroment_url    = $BYS_enviroment;
    }
    
    /*
     * SET ENVIROMENT
     */
    function setEnviromentURL()
    {
        $dev        = "https://dev.testbys.eu/";
        $test       = "https://www.testbys.eu/";
        $production = "https://www.bookyourself.com/api/";
        
        if ($this->enviroment_url == "dev")
            return $dev;
        if ($this->enviroment_url == "test")
            return $test;
        if ($this->enviroment_url == "production")
            return $dev;
        
    }
    
    /*
     * CCREATE URL FOR AUTENTIFIKATION APP
     */
    function createAuthUrl($scope, $state, $access_type, $response_type, $provider_id, $as_json)
    {
        $part = "oauth20/auth";
        $url  = $this->setEnviromentURL() . "$part";
        
        $UrlAuthApp = $url . "?client_id=" . $this->client_id . "&client_secret=" . $this->client_secret . "&access_type=" . $access_type . "&state=" . $state . "&scope=" . $scope . "&redirect_uri=" . $this->redirect_uri . "&response_type=" . $response_type . "&provider_id=" . $provider_id . "&as_json=" . $as_json;
        return $UrlAuthApp;
    }
    
    /*
     * WILL CHANGE ACQUIRED AUTHORIZATION CODE FOR ACCESS TOKEN, REFRESH TOKEN AND EXPIRATION TIME
     */
    function getTokens($auth_code)
    {
        $grant_type = "authorization_code";
        $part       = "oauth20/token";
        $url        = $this->setEnviromentURL() . "$part";
        $toBody     = "client_id=" . $this->client_id . "&client_secret=" . $this->client_secret . "&grant_type=" . $grant_type . "&redirect_uri=" . $this->redirect_uri . "&code=" . $auth_code;
        
        $response = \Httpful\Request::post($url)->contentType("application/x-www-form-urlencoded")->body($toBody)->send();
        
        $status = $response->code;
        try {
            if ($status != 200) {
                throw new Exception('Status Code is not 200.');
            } {
                $TokensAndExpTime = array(
                    'status' => true,
                    'access_token' => $response->body->access_token,
                    'expires_in' => $response->body->expires_in,
                    'refresh_token' => $response->body->refresh_token
                );
            }
        }
        catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
        
        return $TokensAndExpTime;
    }
    
    /*
     * WILL CHANGE REFRESH TOKEN FOR ACCESS TOKEN
     */
    function changeTokens($refresh_token)
    {
        $part       = "oauth20/token";
        $url        = $this->setEnviromentURL() . "$part";
        $grant_type = "refresh_token";
        $toBody     = "client_id=" . $this->client_id . "&client_secret=" . $this->client_secret . "&grant_type=" . $grant_type . "&redirect_uri=" . $this->redirect_uri . "&refresh_token=" . $refresh_token;
        
        $response = \Httpful\Request::post($url)->AddHeaders(array(
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret
        ))->contentType("application/x-www-form-urlencoded")->body($toBody)->send();
        
        $status = $response->code;
        try {
            if ($status != 200) {
                throw new Exception('Status Code is not 200.');
            } {
                $AccessTokenAndTime = array(
                    status => true,
                    access_token => $response->body->access_token,
                    expires_in => $response->body->expires_in
                );
                
            }
        }
        catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
        
        return $AccessTokenAndTime;
    }
    
    /*
     * CREATE IFRAME URL
     */
    function createUrlToIframe($type, $provider_id, $access_token)
    {
        $part = "iframes/";
        $url  = $this->setEnviromentURL() . "$part";
        
        $UrlToIframe = $url . $type . "?providerId=" . $provider_id . "&client_id=" . $this->client_id . "&access_token=" . $access_token;
        
        
        return $UrlToIframe;
    }
    
    /*
     * CREATE FULL IFRAME
     */
    function createIframe($type, $provider_id, $access_token, $width, $height)
    {
        $url = "https://www.testbys.eu/iframes/";
        
        $UrlToIframe = $this->createUrlToIframe($type, $provider_id, $access_token);
        
        $createIframe = '<iframe src="' . $UrlToIframe . '" style="width:' . $width . 'px; height:' . $height . 'px;"><p>Your browser does not support iframes.</p></iframe>';
        
        return $createIframe;
        
    }
    
    /*
     * FIND OLD RESERVATIONS
     */
    function oldReservations($user_id, $provider_id, $startsBefore, $access_token)
    {
        $part = "reservations.json?userId=" . $user_id . "&providerId=" . $provider_id . "&startsBefore=" . $startsBefore . "&limit=1";
        
        $url = $this->setEnviromentURL() . $part;
        
        $response = \Httpful\Request::get($url)->AddHeaders(array(
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
            'access_token' => $access_token
        ))->send();
        
        $status = $response->code;
        try {
            if ($status != 200) {
                throw new Exception('Status Code is not 200.');
            } {
                $count = array(
                    'status' => true,
                    'count' => $response->body->count
                );
            }
        }
        catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
        
        return $count;
    }
    
    public function createProvider($access_token, $name, $ico, $dic, $icdph, $street, $city, $zip, $province, $state, $timezoneId, $defaultLanguage, $description, $phone, $email, $web, $timeBeforeReservation, $createReservationNotification, $updateReservationNotification, $deleteReservationNotification, $scope)
    {
        $part = "providers.json";
        
        $url = $this->setEnviromentURL() . "$part";
        
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
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
            'access_token' => $access_token
        ))->contentType("application/json")->body("$json")->send();
        
        $status = $response->code;
        
        try {
            if ($status != 200) {
                throw new Exception('Status Code is not 200.');
            } {
                
                $createProvider = array(
                    'status' => true,
                    'id' => $response->body->id,
                    'createdAt' => $response->body->createdAt,
                    'updatedAt' => $response->body->updatedAt,
                    'name' => $response->body->name,
                    'ico' => $response->body->ico,
                    'dic' => $response->body->dic,
                    'icdph' => $response->body->icdph,
                    'street' => $response->body->street,
                    'city' => $response->body->city,
                    'zip' => $response->body->zip,
                    'province' => $response->body->province,
                    'state' => $response->body->state,
                    'timezoneId' => $response->body->timezoneId,
                    'timezoneOffset' => $response->body->timezoneOffset,
                    'summerTimeOffset' => $response->body->summerTimeOffset,
                    'defaultLanguage' => $response->body->defaultLanguage,
                    'description' => $response->body->description,
                    'phone' => $response->body->phone,
                    'email' => $response->body->email,
                    'web' => $response->body->web,
                    'userId' => $response->body->userId,
                    'timeBeforeReservation' => $response->body->timeBeforeReservation,
                    'createReservationNotification' => $response->body->createReservationNotification,
                    'updateReservationNotification' => $response->body->updateReservationNotification,
                    'deleteReservationNotification' => $response->body->deleteReservationNotification,
                    'acces_token' => $response->body->access_token,
                    'refresh_token' => $response->body->refresh_token,
                    'expire_in' => $response->body->expires_in
                    
                );
            }
        }
        
        catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
        
        return $createProvider;
        
    }
    
    /*
     * DELETE PROVIDER
     */
    public function deleteProvider($id_provider, $access_token)
    {
        $part = "providers/$id_provider.json";
        $url  = $this->setEnviromentURL() . "$part";
        
        $response = \Httpful\Request::delete($url)->AddHeaders(array(
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
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
    
    
    /*
     * CREATE USER
     */
    public function createUser($firstName, $lastName, $email, $password, $passwordAgain, $phone, $activated)
    {
        $part     = "users.json";
        $url      = $this->setEnviromentURL() . "$part";
        $jsonData = array(
            'firstName' => $firstName,
            'lastName' => $lastName,
            'email' => $email,
            'password' => $password,
            'passwordAgain' => $passwordAgain,
            'phone' => $phone,
            'activated' => $activated
        );
        $json     = json_encode($jsonData);
        
        $response = \Httpful\Request::post($url)->AddHeaders(array(
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret
        ))->contentType("application/json")->body("$json")->send();
        
        $status = $response->code;
        try {
            if ($status != 200) {
                throw new Exception('Status Code is not 200.');
            } {
                $createUser = array(
                    'status' => true,
                    'id' => $response->body->id,
                    'createdAt' => $response->body->createdAt,
                    'updatedAt' => $response->body->updatedAt,
                    'email' => $response->body->email,
                    'firstName' => $response->body->firstName,
                    'lastName' => $response->body->lastName,
                    'phone' => $response->body->phone,
                    'activated' => $response->body->activated,
                    'isNew' => $response->body->isNew
                );
            }
        }
        catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
        
        
        return $createUser;
        
    }
    
    public function addScope($scope, $email, $password)
    {
        $part = "oauth20/auth?scope=" . $scope . "&redirect_uri=" . $this->redirect_uri . "&response_type=code&as_json=true&client_id=" . $this->client_id;
        
        $url = $this->setEnviromentURL() . "$part";
        
        $response = \Httpful\Request::post($url)->AddHeaders(array(
            'email' => $email,
            'password' => $password
        ))->contentType("application/x-www-form-urlencoded")->body('action=grant')->send();
        
        $status = $response->code;
        try {
            if ($status != 200) {
                throw new Exception('Status Code is not 200.');
            } {
                $code = array(
                    'status' => true,
                    'id' => $response->body->id,
                    'userId' => $response->body->userId,
                    'providerId' => $response->body->providerId,
                    'appId' => $response->body->appId,
                    'expire' => $response->body->expire,
                    'type' => $response->body->type
                );
                
            }
        }
        catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
  
        return $code;
    }
    
}



?>
