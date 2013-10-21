<?php
require_once("httpful.phar");
require_once("config.php");
require_once("bys-client.php");

class BysUser
{
    /*
     * CREATE USER
     */
    public function createUser($firstName, $lastName, $email, $password, $passwordAgain, $phone, $activated)
    {
        global $client_id;
        global $client_secret;
        $part     = "users.json";
        $client   = new BysClient();
        $url      = $client->setEnviromentURL() . "$part";
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
            'client_id' => $client_id,
            'client_secret' => $client_secret
        ))->contentType("application/json")->body("$json")->send();
        
        $status = $response->code;
        if ($status == 200) {
            
            $createUser = array(
                status => true,
                id => $response->body->id,
                createdAt => $response->body->createdAt,
                updatedAt => $response->body->updatedAt,
                email => $response->body->email,
                firstName => $response->body->firstName,
                lastName => $response->body->lastName,
                phone => $response->body->phone,
                activated => $response->body->activated,
                isNew => $response->body->isNew
            );
            
        } else {
            $createUser = array(
                status => false
            );
        }
        
        return $createUser;
        
    }
    
    
    public function addScope($scope, $email, $password)
    {
        global $client_id;
        global $client_secret;
        global $redirect_uri;
        
        $part   = "oauth20/auth?scope=$scope&redirect_uri=$redirect_uri&response_type=code&as_json=true&client_id=$client_id";
        $client = new BysClient();
        $url    = $client->setEnviromentURL() . "$part";
        
        $response = \Httpful\Request::post($url)->AddHeaders(array(
            'email' => $email,
            'password' => $password
        ))->contentType("application/x-www-form-urlencoded")->body('action=grant')->send();
        
        $status = $response->code;
        if ($status == 200) {
            
            $code = array(
                status => true,
                id => $response->body->id,
                userId => $response->body->userId,
                providerId => $response->body->providerId,
                appId => $response->body->appId,
                expire => $response->body->expire,
                type => $response->body->type
            );
            
        }
        
        else {
            $code = array(
                status => false
            );
        }
        
        return $code;
        
    }
    
}

?>
