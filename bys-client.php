<?php
if(file_exists ( dirname(__FILE__)."/httpful.phar" )) {
	require_once(dirname(__FILE__)."/httpful.phar");
}

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
        
        if ($i == 4) {
            call_user_func_array(array(
                $this,
                $f
            ), $a);
        }
        
        else {
            try {
                if(file_exists ( dirname(__FILE__)."/config.php" )) {
					require_once(dirname(__FILE__)."/config.php");
				} else {
				global $BYS_client_id;
				global $BYS_client_secret;
				global $BYS_redirect_uri;
				global $BYS_enviroment;
				}
                
                if (empty($BYS_client_id) || empty($BYS_client_secret) || empty($BYS_redirect_uri) || empty($BYS_enviroment)) {
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
        $this->client_id      = $BYS_client_id;
        $this->client_secret  = $BYS_client_secret;
        $this->redirect_uri   = $BYS_redirect_uri;
        $this->enviroment_url = $BYS_enviroment;
    }
    
    /*
     * SET ENVIROMENT
     */
    public function setEnviromentURL()
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
    public function createAuthUrl($scope, $state, $access_type, $response_type, $provider_id, $as_json)
    {
        $part = "oauth20/auth";
        $url  = $this->setEnviromentURL() . "$part";
        
        $UrlAuthApp = $url . "?client_id=" . $this->client_id . "&client_secret=" . $this->client_secret . "&access_type=" . $access_type . "&state=" . $state . "&scope=" . $scope . "&redirect_uri=" . $this->redirect_uri . "&response_type=" . $response_type . "&provider_id=" . $provider_id . "&as_json=" . $as_json;
        return $UrlAuthApp;
    }
    
    /*
     * WILL CHANGE ACQUIRED AUTHORIZATION CODE FOR ACCESS TOKEN, REFRESH TOKEN AND EXPIRATION TIME
     */
    public function getTokens($auth_code)
    {
        $grant_type = "authorization_code";
        $part       = "oauth20/token";
        $url        = $this->setEnviromentURL() . "$part";
        $toBody     = "client_id=" . $this->client_id . "&client_secret=" . $this->client_secret . "&grant_type=" . $grant_type . "&redirect_uri=" . $this->redirect_uri . "&code=" . $auth_code;
        
        $response = \Httpful\Request::post($url)->contentType("application/x-www-form-urlencoded")->body($toBody)->send();
        
        if ($response->code == 200) {
            $TokensAndExpTime = array(
                'status' => $response->code,
                'access_token' => $response->body->access_token,
                'expires_in' => $response->body->expires_in,
                'refresh_token' => $response->body->refresh_token
            );
        } else {
            $TokensAndExpTime = array(
                'status' => $response->code
            );
        }
        
        return $TokensAndExpTime;
    }
    
    /*
     * WILL CHANGE REFRESH TOKEN FOR ACCESS TOKEN
     */
    public function changeTokens($refresh_token)
    {
        $part       = "oauth20/token";
        $url        = $this->setEnviromentURL() . "$part";
        $grant_type = "refresh_token";
        $toBody     = "client_id=" . $this->client_id . "&client_secret=" . $this->client_secret . "&grant_type=" . $grant_type . "&redirect_uri=" . $this->redirect_uri . "&refresh_token=" . $refresh_token;
        
        $response = \Httpful\Request::post($url)->AddHeaders(array(
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret
        ))->contentType("application/x-www-form-urlencoded")->body($toBody)->send();
        
        if ($response->code == 200) {
            $AccessTokenAndTime = array(
                'status' => $response->code,
                'access_token' => $response->body->access_token,
                'expires_in' => $response->body->expires_in
            );
        } else {
            $AccessTokenAndTime = array(
                'status' => $response->code
            );
        }
        
        return $AccessTokenAndTime;
    }
    
    /*
     * CREATE IFRAME URL
     */
    public function createUrlToIframe($type, $provider_id, $access_token)
    {
        $part = "iframes/";
        $url  = $this->setEnviromentURL() . "$part";
        
        $UrlToIframe = $url . $type . "?providerId=" . $provider_id . "&client_id=" . $this->client_id . "&access_token=" . $access_token;
        
        return $UrlToIframe;
    }
    
    /*
     * CREATE FULL IFRAME
     */
    public function createIframe($type, $provider_id, $access_token, $width, $height)
    {
        $UrlToIframe = $this->createUrlToIframe($type, $provider_id, $access_token);
        
        $createIframe = '<iframe src="' . $UrlToIframe . '" style="width:' . $width . 'px; height:' . $height . 'px;"><p>Your browser does not support iframes.</p></iframe>';
        
        return $createIframe;
        
    }
    
    /*
     * FIND OLD RESERVATIONS
     */
    public function oldReservations($user_id, $provider_id, $startsBefore, $access_token)
    {
        $part = "reservations.json?userId=" . $user_id . "&providerId=" . $provider_id . "&startsBefore=" . $startsBefore . "&limit=1";
        
        $url = $this->setEnviromentURL() . $part;
        
        $response = \Httpful\Request::get($url)->AddHeaders(array(
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
            'access_token' => $access_token
        ))->send();
        
        if ($response->code == 200) {
            $count = array(
                'status' => $response->code,
                'count' => $response->body->count
            );
        } else {
            $count = array(
                'status' => $response->code
            );
        }
        
        return $count;
    }
    
    /*
     * CREATE PROVIDER
     */
    public function createProvider($newProvider, $access_token)
    {
        $part = "providers.json";
        
        $url = $this->setEnviromentURL() . "$part";
        
        $jsonData = array(
            'name' => $newProvider->name,
            'ico' => $newProvider->ico,
            'dic' => $newProvider->dic,
            'icdph' => $newProvider->icdph,
            'street' => $newProvider->street,
            'city' => $newProvider->city,
            'zip' => $newProvider->zip,
            'province' => $newProvider->province,
            'state' => $newProvider->state,
            'timezoneId' => $newProvider->timezoneId,
            'defaultLanguage' => $newProvider->defaultLanguage,
            'description' => $newProvider->description,
            'phone' => $newProvider->phone,
            'email' => $newProvider->email,
            'web' => $newProvider->web,
            'timeBeforeReservation' => $newProvider->timeBeforeReservation,
            'createReservationNotification' => $newProvider->createReservationNotification,
            'updateReservationNotification' => $newProvider->updateReservationNotification,
            'deleteReservationNotification' => $newProvider->deleteReservationNotification,
            'scope' => $newProvider->scope
        );
        $json     = json_encode($jsonData);
        
        $response = \Httpful\Request::post($url)->AddHeaders(array(
            'Cache-Control' => 'no-cache',
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
            'access_token' => $access_token
        ))->contentType("application/json")->body("$json")->send();
        
        if ($response->code == 200) {
            
            $createProvider = array(
                'status' => $response->code,
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
        } else {
            $createProvider = array(
                'status' => $response->code
            );
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
        
        if ($response->code == 200) {
            $return = true;
        } else {
            $return = false;
        }
        
        return $return;
    }
    
    
    /*
     * CREATE USER
     */
    public function createUser($newUser)
    {
        $part     = "users.json";
        $url      = $this->setEnviromentURL() . "$part";
        $jsonData = array(
            'firstName' => $newUser->firstName,
            'lastName' => $newUser->lastName,
            'email' => $newUser->email,
            'password' => $newUser->password,
            'passwordAgain' => $newUser->passwordAgain,
            'phone' => $newUser->phone,
            'activated' => $newUser->activated
        );
        $json     = json_encode($jsonData);
        
        $response = \Httpful\Request::post($url)->AddHeaders(array(
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret
        ))->contentType("application/json")->body("$json")->send();
        
        if ($response->code == 200) {
            $createUser = array(
                'status' => $response->code,
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
        } else {
            $createUser = array(
                'status' => $response->code
            );
        }
        
        return $createUser;
    }
    
    /*
     * ADD SCOPE
     */
    
    public function addScope($scope, $email, $password)
    {
        $part = "oauth20/auth?scope=" . $scope . "&redirect_uri=" . $this->redirect_uri . "&response_type=code&as_json=true&client_id=" . $this->client_id;
        
        $url = $this->setEnviromentURL() . $part;
        
        $response = \Httpful\Request::post($url)->AddHeaders(array(
            'email' => $email,
            'password' => $password
        ))->contentType("application/x-www-form-urlencoded")->body('action=grant')->send();
        
        if ($response->code == 200) {
            $result = array(
                'status' => $response->code,
                'id' => $response->body->id,
                'userId' => $response->body->userId,
                'providerId' => $response->body->providerId,
                'appId' => $response->body->appId,
                'expire' => $response->body->expire,
                'type' => $response->body->type
            );
        } else {
            $result = array(
                'status' => $response->code
            );
        }
        
        return $result;
    }
    
    
    /*
     * CONTROL ACCESS TOKEN
     */
    public function controlAccessToken($token_id)
    {
        $part = "oauth20/tokeninfo?client_id=" . $this->client_id . "&client_secret=" . $this->client_secret . "&token_id=" . $token_id;
        
        $url = $this->setEnviromentURL() . $part;
        
        $response = \Httpful\Request::get($url)->contentType("application/x-www-form-urlencoded")->send();
        
        if ($response->code == 200) {
            $result = array(
                'status' => $response->code,
                'id' => $response->body->id,
                'userId' => $response->body->userId,
                'providerId' => $response->body->providerId,
                'appId' => $response->body->appId,
                'expire' => $response->body->expire,
                'type' => $response->body->type
            );
        } else {
            $result = array(
                'status' => $response->code
            );
        }
        
        return $result;
    }
}


?>
