<?php
include_once 'scripts/adodb_lite/adodb.inc.php';
include_once 'scripts/adodb_lite/adodb-exceptions.inc.php'; 

class Finish {   
    
	function execute()
	{
		echo "<p>You have successfully completed the installer, and Fuji is ready to use!</p>";
		$file = fopen('scripts/configuration/installer_disabled', 'wb');
		fwrite($file, 'This file disables the Fuji installer, delete this file if you want to reinstall');
		fclose($file);
	}
}

?>
