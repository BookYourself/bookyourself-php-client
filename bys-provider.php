<?php
require_once("../bys-client.php");

class BysProvider
{ 
    /*
     * CREATE PROVIDER
     */
          
    public function __construct($name, $ico, $dic, $icdph, $street, $city, $zip, $province, $state, $timezoneId, $defaultLanguage, $description, $phone, $email, $web, $timeBeforeReservation, $createReservationNotification, $updateReservationNotification, $deleteReservationNotification, $scope)
    {
		$this->name = $name;
		$this->ico = $ico;
		$this->dic = $dic;
		$this->icdph = $icdph;
		$this->street = $street;
		$this->city = $city;
		$this->zip = $zip;
		$this->province = $province;
		$this->state = $state;
		$this->timezoneId = $timezoneId;
		$this->defaultLanguage = $defaultLanguage;
		$this->description = $description;
		$this->phone = $phone;
		$this->email = $email;
		$this->web = $web;
		$this->timeBeforeReservation = $timeBeforeReservation;
		$this->createReservationNotification = $createReservationNotification;
		$this->updateReservationNotification = $updateReservationNotification;
		$this->deleteReservationNotification = $deleteReservationNotification;
		$this->scope = $scope;
	}
		
	public function create($access_token)
    {
		$bysClient = new BysClient();
		$result = $bysClient->createProvider($this, $access_token);
        
        return $result;
        
    }
    
    /*
     * DELETE PROVIDER
     */
    public function delete($id_provider, $access_token)
    {
		$bysClient = new BysClient();
		$result = $bysClient->deleteProvider($id_provider, $access_token);
        
        return $result;

    }
    
}

?>
