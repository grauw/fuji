<?php

require_once "usermanager.php";

UserManager::forceAuthenticate();
if($_GET['action'] == 'logout')
{
	//header('WWW-Authenticate: Basic realm="Forum"');
	//header('HTTP/1.0 401 Unauthorized');
	//$_SERVER['PHP_AUTH_USER'] = 'anonymous';
	//$_SERVER['PHP_AUTH_PW'] = '';
	
	unset($_SERVER['PHP_AUTH_USER']);
	unset($_SERVER['PHP_AUTH_PW']);
	
	$_SERVER['PHP_AUTH_USER'] = 'anonymous';
	$_SERVER['PHP_AUTH_PW'] = '';
}
if($_GET['action'] == 'login')
{
	header('WWW-Authenticate: Basic realm="Forum"');
	header('HTTP/1.0 401 Unauthorized');
}
?>