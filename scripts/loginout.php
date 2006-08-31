<?php
require_once "usermanager.php";

if ($_COOKIE['action'] == 'authenticate') {
	setcookie('action', '');
	if(!UserManager::isUser()) {
		UserManager::authenticate(false);
	}
	else {
		UserManager::authenticate(true);
	}
}
else if($_GET['action'] == 'logout') {
	setcookie('action', 'authenticate');
	UserManager::sendDigestHeader();
}
else if($_GET['action'] == 'login') {
	setcookie('action', 'authenticate');
	UserManager::sendDigestHeader();
}

?>