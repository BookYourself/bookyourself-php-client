<?php

//Example URL:
//http://www.example.org/Simple/deleteprovider.php?provider_id=1648&access_token=kKqEbj4Ry1MosUXSpB8QJBqDo5ytngrf

require_once('../bys-provider.php');

session_start();

/*
 * DELETE PROVIDER
 */

// First determine if session provider_id and access_token are set and aren't NULL. 
if (isset($_GET['provider_id']) && isset($_GET['access_token'])) {
    
    // Next call method "deleteProvider" which return info if user did deleted.
    $delete = new BysProvider();
    $del    = $delete->deleteProvider($_GET['provider_id'], $_GET['access_token']);
    
    // Control returned value.
    if ($del == 1) {
        echo 'The user has been deleted.';
    } else {
        echo error;
    }
} else {
    echo error;
}

?>
