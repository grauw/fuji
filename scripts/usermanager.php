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
				if($user && $user->checkPassword($pwd))
				{
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
		static function authenticate($force=false)
		{
			if($force)
			{		
				//self::forceAuthenticate();
				if (!isset($_SERVER['PHP_AUTH_USER'])) {
					header('WWW-Authenticate: Basic realm="Forum"');
					header('HTTP/1.1 401 Unauthorized');
					//$_SERVER['PHP_AUTH_USER'] = 'anonymous';
					//$_SERVER['PHP_AUTH_PW'] = 'da39a3ee5e6b4b0d3255bfef95601890afd80709';
					// 2times sha1 is not a good idea!
					//$_SERVER['PHP_AUTH_PW'] = '';
	
				} else {
					if(UserManager::logIn($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW'])) {
					}
					else {
						header('WWW-Authenticate: Basic realm="Forum"');
						header('HTTP/1.1 401 Unauthorized');
						//$_SERVER['PHP_AUTH_USER'] = 'anonymous';
						//$_SERVER['PHP_AUTH_PW'] = 'da39a3ee5e6b4b0d3255bfef95601890afd80709';
						//$_SERVER['PHP_AUTH_PW'] = '';
	
						//self::forceAuthenticate();
					}
				}
			}
			else
			{
				UserManager::LogIn('anonymous', '');
			}
		}
		
		static function forceAuthenticate()
		{
			//unset($_SERVER['PHP_AUTH_USER']);
			//unset($_SERVER['PHP_AUTH_PW']);
			
			header('WWW-Authenticate: Basic realm="Forum"');
			header('HTTP/1.1 401 Unauthorized');
			if(UserManager::logIn($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']))
			{
				
			}
			//$_SERVER['PHP_AUTH_USER'] = 'anonymous';
			//$_SERVER['PHP_AUTH_PW'] = 'da39a3ee5e6b4b0d3255bfef95601890afd80709';
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
		
		static function isAdmin()
		{
			if(!isset(self::$user))
				if(isset($_SERVER['PHP_AUTH_USER']))
					self::$user = User::getUserByName($_SERVER['PHP_AUTH_USER']);
				else
					self::$user = User::getUserByName('anonymous');
			return self::$user->isAdmin();
		}
	}	
?>
