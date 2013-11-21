<?php
require_once(dirname(__FILE__) . "/bys-client.php");

class BysUser
{
	public function __construct($firstName, $lastName, $email, $password, $passwordAgain, $phone, $activated) 
	{
	$this->firstName = $firstName;
	$this->lastName = $lastName;
	$this->email = $email;
	$this->password = $password;
	$this->passwordAgain = $passwordAgain;
	$this->phone = $phone;
	$this->activated = $activated;
	}
	
	
    /*
     * CREATE USER
     */
    public function create()
    {
        $bysClient = new BysClient();
		$result = $bysClient->createUser($this);
        
        return $result;
        
    }
    
    public function addScope($scope, $email, $password)
    {
		$bysClient = new BysClient();
		$result = $bysClient->addScope($scope, $email, $password);
        
        return $result;
        
    }
    
}

?>
