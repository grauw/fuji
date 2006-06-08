<?php

require_once "usermanager.php";

if($_GET['action'] == 'logout')
{	
	setcookie('anonymous','true');
}
else if($_GET['action'] == 'login')
{
	setcookie('anonymous','false');
}
?>