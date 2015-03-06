<?php
//Example URL:
//http://www.example.org/Simple/deleteprovider.php?provider_id=1648&access_token=kKqEbj4Ry1MosUXSpB8QJBqDo5ytngrf

require_once('../bys-client.php');
require_once('./config.php');

session_start();

$client = new BysClient();

/*
 * DELETE PROVIDER
 */

// First determine if session provider_id and access_token are set and aren't NULL. 
try {

	if (empty($_GET['provider_id']) || empty($_GET['access_token'])) {
		throw new Exception( 'Provider ID or Access token is null.' );
	} else {
		// Next call method "deleteProvider" which return info if user did deleted.
		$delete    = $client->deleteProvider($_GET['provider_id'], $_GET['access_token']);
		
		// Control returned value.
		if ($delete == true) {
			echo 'The provider has been deleted.';
		} else {
			echo 'The provider has not been deleted.';
		}
	}

} catch (Exception $e) {
	echo 'Error: '. $e->getMessage();
}


?>
