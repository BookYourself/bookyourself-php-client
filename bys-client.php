<?php
include("httpful.phar");
include("config.php");

class Bys_Client
{
    // create url for autentifikation app
    public function createAuthUrl($scope, $access_type, $state, $provider_id, $as_json)
    {
        global $client_id;
        global $client_secret;
        global $redirect_uri;
        $url           = "https://www.testbys.eu/oauth20/oauth";
        $response_type = "code";
        
        $UrlAuthApp = "$url" . "?client_id=$client_id" . "&client_secret=$client_secret" . "&access_type=$access_type" . "&state=$state" . "&scope=$scope" . "&redirect_uri=$redirect_uri" . "&response_type=$response_type" . "&provider_id=$provider_id" . "&as_json=$as_json";
        
        return $UrlAuthApp;
    }
    
    // change acquired authorization code for access token, refresh token and expiration time
    public function getTokens($auth_code)
    {
        global $client_id;
        global $client_secret;
        $url        = "https://www.testbys.eu/oauth20/token";
        $grant_type = "authorization_code";
        
        $response         = \Httpful\Request::post($url)->AddHeaders(array(
            'client_id' => $client_id,
            'client_secret' => $client_secret
        ))->contentType("application/x-www-form-urlencoded")->body("client_id=$client_id" . "&client_secret=$client_secret" . "&grant_type=$grant_type" . "&redirect_uri=http%3A%2F%2Fnieco.nieco.nie%2F" . "&auth_code=$auth_code")->send();
        $TokensAndExpTime = array(
            $response->body->access_token,
            $response->body->expires_in,
            $response->body->refresh_token
        );
        return $TokensAndExpTime;
    }
    
    
    
    
    // change refresh token for access token
    public function changeTokens($refresh_token)
    {
        global $client_id;
        global $client_secret;
        $url        = "https://www.testbys.eu/oauth20/token";
        $grant_type = "refresh_token";
        
        $response           = \Httpful\Request::post($url)->AddHeaders(array(
            'client_id' => $client_id,
            'client_secret' => $client_secret
        ))->contentType("application/x-www-form-urlencoded")->body("client_id=$client_id" . "&client_secret=$client_secret" . "&grant_type=$grant_type" . "&redirect_uri=http%3A%2F%2Fnieco.nieco.nie%2F" . "&refresh_token=$refresh_token")->send();
        $AccessTokenAndTime = array(
            $response->body->access_token,
            $response->body->expires_in
        );
        return $AccessTokenAndTime;
    }
    
    
    // create provider 
    public function createProvider($access_token, $name, $ico, $dic, $icdph, $street, $city, $zip, $province, $state, $timezoneId, $defaultLanguage, $description, $phone, $email, $web, $userId, $timeBeforeReservation, $createReservationNotifikation, $updateReservationNotifikation, $deleteReservationNotifikation)
    {
        global $client_id;
        global $client_secret;
        $url        = "https://www.testbys.eu/provider.json";
        $grant_type = "refresh_token";
        
        $response = \Httpful\Request::post($url)->AddHeaders(array(
            'Content-Type' => 'application/json',
            'Cache-Control' => 'no-cache',
            'client_id' => $client_id,
            'client_secret' => $client_secret,
            'access_token' => $access_token
        ))->sendsJson()->body('{"name":"$name"}', '{"ico":"$ico"}', '{"dic":"$dic"}', '{"icdph":"$icdph"}', '{"street":"$street"}', '{"city":"$city"}', '{"zip":"$zip"}', '{"province":"$province"}', '{"state":"$state"}', '{"timezoneId":"$timezoneId"}', '{"defaultLanguage":"$defaultLanguage"}', '{"description":"$description"}', '{"phone":"$phone"}', '{"email":"$email"}', '{"web":"$web"}', '{"userId":"$userId"}', '{"timeBeforeReservation":"$timeBeforeReservation"}', '{"createReservationNotifikation":"$createReservationNotifikation"}', '{"updateReservationNotifikation":"$updateReservationNotifikation"}', '{"deleteReservationNotifikation":"$deleteReservationNotifikation"}')->send();
        /*
        $createProvider = array(
        $response->body->,
        
        );
        * */
        return $createProvider;
        
    }
    
    // delete provider
    public function deleteProvider($access_token, $id_provider)
    {
        
        $url = "https://www.testbys.eu/providers/$id_provider.json";
        
        $response = \Httpful\Request::delete($url)->AddHeaders(array(
            'client_id' => $client_id,
            'client_secret' => $client_secret,
            'access_token' => $access_token
        ))->send();
        
        return;
        
    }
    
    // create user
    public function createUser($access_token, $email, $password, $passwordAgain, $firstName, $lastName, $phone, $activated)
    {
        global $client_id;
        global $client_secret;
        $url        = "https://www.testbys.eu/provider.json";
        $grant_type = "refresh_token";
        
        $response = \Httpful\Request::post($url)->AddHeaders(array(
            'Content-Type' => 'application/json',
            'Cache-Control' => 'no-cache',
            'client_id' => $client_id,
            'client_secret' => $client_secret,
            'access_token' => $access_token
        ))->sendsJson()->body('{"email":"$email",
            "password":"$password",
            "passwordAgain":"$passwordAgain",
            "firstName":"$firstName",
            "lastName":"$lastName",
            "phone":"$phone",
            "activated":"$activated"
            }')->send();
        
        $createProvider = array(
            $response->body->id,
            $response->body->createdAt,
            $response->body->updatedAt,
            $response->body->email,
            $response->body->firstName,
            $response->body->lastName,
            $response->body->phone,
            $response->body->activated,
            $response->body->isNew
        );
        return $createProvider;
        
    }
    
    
    // create iframe URL
    public function createUrlToIframe($type, $provider_id, $access_token, $refresh_token)
    {
        global $client_id;
        global $client_secret;
        $url          = "https://www.testbys.eu/iframes/";
        $access_token = "QL3xvzPwop7wQ8m0td5yyK07oarCSlmq";
        
        
        $UrlToIframe = "$url" . "$type" . "?providerId=$provider_id" . "&client_id=$client_id" . "&access_token=$access_token";
        
        
        return $UrlToIframe;
    }
    
    // create iframe full
    public function createIframe($type, $provider_id, $access_token, $refresh_token, $width, $height)
    {
        $url = "https://www.testbys.eu/iframes/";
        global $client_id;
        global $client_secret;
        
        $UrlToIframe = "$url" . "$type" . "?providerId=$provider_id" . "&client_id=$client_id" . "&access_token=$access_token";
        
        $createIframe = '<iframe src="' . $UrlToIframe . '" style="width:' . $width . 'px; height:' . $height . 'px;"><p>Your browser does not support iframes.</p></iframe>';
        
        return $createIframe;
        
    }
    
}

?>
