<?php
	require_once "database.php";
	//require_once "sessionmanager.php";
	require_once "user.php";
	
	class UserManager {
		static $user = null;
		
		static function logIn($nickname, $pwd)
		{
			if(!$_SESSION['user'])
			{
				Database::connect();
				$user = User::getUserByName($nickname);
				if($user->checkPassword($pwd))
				{
					//$_SESSION['user'] = $user->getId();
					return true;
				}
				else
					return false;
			}
			return true;
		}
		static function logOut()
		{
			unset($_SESSION['user']);
		}
		static function authenticate()
		{
			if (!isset($_SERVER['PHP_AUTH_USER'])) {
				header('WWW-Authenticate: Basic realm="Forum"');
				header('HTTP/1.0 401 Unauthorized');
				$_SERVER['PHP_AUTH_USER'] = 'anonymous';
				$_SERVER['PHP_AUTH_PW'] = 'da39a3ee5e6b4b0d3255bfef95601890afd80709';
			} else {
				if(UserManager::logIn($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW'])) {
				}
				else {
					header('WWW-Authenticate: Basic realm="Forum"');
					header('HTTP/1.0 401 Unauthorized');
					$_SERVER['PHP_AUTH_USER'] = 'anonymous';
					$_SERVER['PHP_AUTH_PW'] = 'da39a3ee5e6b4b0d3255bfef95601890afd80709';
				}
			}
		}
		static function getUser()
		{
			if(!isset(self::$user))
				if(isset($_SERVER['PHP_AUTH_USER']))
					self::$user = User::getUserByName($_SERVER['PHP_AUTH_USER']);
				else
					self::$user = User::getUserByName('anonymous');
			return self::$user;
		}
	}	
?>
