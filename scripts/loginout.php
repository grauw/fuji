<?php

require_once "usermanager.php";

if ($_COOKIE['action'] == 'authenticate') {
	setcookie('action', '');
	if(!isset($_SERVER['PHP_AUTH_USER'])) {
		$_SERVER['PHP_AUTH_USER'] = 'anonymous';
		$_SERVER['PHP_AUTH_PW'] = '';
		UserManager::authenticate(false);
	}
	else {
		echo $_SERVER['PHP_AUTH_USER'];
		UserManager::authenticate(true);
	}
}
else if($_GET['action'] == 'logout') {
	setcookie('action', 'authenticate');
	header('WWW-Authenticate: Basic realm="Forum"');
	header('HTTP/1.0 401 Unauthorized');
}
else if($_GET['action'] == 'login') {
	setcookie('action', 'authenticate');
	header('WWW-Authenticate: Basic realm="Forum"');
	header('HTTP/1.0 401 Unauthorized');
}

?>