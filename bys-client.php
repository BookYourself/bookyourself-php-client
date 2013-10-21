<?php
require_once("httpful.phar");
require_once("config.php");

class BysClient
{
    /*
     * SET ENVIROMENT
     */
    public function setEnviromentURL()
    {
        global $enviroment;
        
        $dev        = "https://dev.testbys.eu/";
        $test       = "https://www.testbys.eu/";
        $production = "https://www.bookyourself.com/api/";
        
        if ($enviroment == "dev")
            return $dev;
        if ($enviroment == "test")
            return $test;
        if ($enviroment == "production")
            return $dev;

    }
    
    /*
     * CCREATE URL FOR AUTENTIFIKATION APP
     */
    public function createAuthUrl($scope, $state, $access_type, $response_type, $provider_id, $as_json)
    {
        global $client_id;
        global $client_secret;
        global $redirect_uri;
        $part          = "oauth20/auth";
        $response_type = "code";
        $client        = new BysClient();
        $url           = $client->setEnviromentURL() . "$part";
        
        $UrlAuthApp = "$url" . "?client_id=$client_id" . "&client_secret=$client_secret" . "&access_type=$access_type" . "&state=$state" . "&scope=$scope" . "&redirect_uri=$redirect_uri" . "&response_type=$response_type" . "&provider_id=$provider_id" . "&as_json=$as_json";
        
        return $UrlAuthApp;
    }
    
    /*
     * WILL CHANGE ACQUIRED AUTHORIZATION CODE FOR ACCESS TOKEN, REFRESH TOKEN AND EXPIRATION TIME
     */
    public function getTokens($auth_code)
    {
        global $client_id;
        global $client_secret;
        global $redirect_uri;
        $grant_type = "authorization_code";
        $part       = "oauth20/token";
        $client     = new BysClient();
        $url        = $client->setEnviromentURL() . "$part";
        $toBody     = "client_id=$client_id" . "&client_secret=$client_secret" . "&grant_type=$grant_type" . "&redirect_uri=$redirect_uri" . "&code=$auth_code";
        
        $response = \Httpful\Request::post($url)->contentType("application/x-www-form-urlencoded")->body($toBody)->send();
        
        $status = $response->code;
        if ($status == 200) {
            
            $TokensAndExpTime = array(
                'status' => true,
                'access_token' => $response->body->access_token,
                'expires_in' => $response->body->expires_in,
                'refresh_token' => $response->body->refresh_token
            );
            
        } else {
            $TokensAndExpTime = array(
                status => false
            );
        }
        
        return $TokensAndExpTime;
    }
    
    /*
     * WILL CHANGE REFRESH TOKEN FOR ACCESS TOKEN
     */
    public function changeTokens($refresh_token)
    {
        global $client_id;
        global $client_secret;
        global $redirect_uri;
        $part       = "oauth20/token";
        $client     = new BysClient();
        $url        = $client->setEnviromentURL() . "$part";
        $grant_type = "refresh_token";
        
        $response = \Httpful\Request::post($url)->AddHeaders(array(
            'client_id' => $client_id,
            'client_secret' => $client_secret
        ))->contentType("application/x-www-form-urlencoded")->body("client_id=$client_id" . "&client_secret=$client_secret" . "&grant_type=$grant_type" . "&redirect_uri=$redirect_uri" . "&refresh_token=$refresh_token")->send();
        
        $status = $response->code;
        if ($status == 200) {
            
            $AccessTokenAndTime = array(
                status => true,
                access_token => $response->body->access_token,
                expires_in => $response->body->expires_in
            );
            
        } else {
            $AccessTokenAndTime = array(
                status => false
            );
        }
        
        return $AccessTokenAndTime;
    }
    
    /*
     * CREATE IFRAME URL
     */
    public function createUrlToIframe($type, $provider_id, $access_token)
    {
        global $client_id;
        global $client_secret;
        $part   = "iframes/";
        $client = new BysClient();
        $url    = $client->setEnviromentURL() . "$part";
        
        $UrlToIframe = "$url" . "$type" . "?providerId=$provider_id" . "&client_id=$client_id" . "&access_token=$access_token";
        
        
        return $UrlToIframe;
    }
    
    /*
     * CREATE FULL IFRAME
     */
    public function createIframe($type, $provider_id, $access_token, $width, $height)
    {
        $url = "https://www.testbys.eu/iframes/";
        global $client_id;
        global $client_secret;
        
        $client      = new BysClient();
        $UrlToIframe = $client->createUrlToIframe($type, $provider_id, $access_token);
        
        $createIframe = '<iframe src="' . $UrlToIframe . '" style="width:' . $width . 'px; height:' . $height . 'px;"><p>Your browser does not support iframes.</p></iframe>';
        
        return $createIframe;
        
    }
    
    /*
     * FIND OLD RESERVATIONS
     */
    public function oldReservations($user_id, $provider_id, $startsBefore, $access_token)
    {
        global $client_id;
        global $client_secret;
        $part = "reservations.json?userId=$user_id&providerId=$provider_id&startsBefore=$startsBefore&limit=1";
        
        $client = new BysClient();
        $url    = $client->setEnviromentURL() . $part;
        
        $response = \Httpful\Request::get($url)->AddHeaders(array(
            'client_id' => $client_id,
            'client_secret' => $client_secret,
            'access_token' => $access_token
        ))->body("client_id=$client_id" . "&client_secret=$client_secret" . "&grant_type=$grant_type" . "&redirect_uri=$redirect_uri" . "&refresh_token=$refresh_token")->send();
        
        $status = $response->code;
        if ($status == 200) {
            
            $count = array(
                status => true,
                count => $response->body->count
            );
            
        } else {
            $count = array(
                status => false
            );
        }
        
        return $count;
    }
}



?>
