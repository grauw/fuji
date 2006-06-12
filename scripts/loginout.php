<?php

require_once "usermanager.php";

if($_GET['action'] == 'logout'
{
	header('WWW-Authenticate: Basic realm="Forum"');
	header('HTTP/1.0 401 Unauthorized');
}
else if($_GET['action'] == 'login')
{
	setcookie('anonymous','false');
}
?>