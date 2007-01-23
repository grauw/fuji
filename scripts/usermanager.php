<?php
	require_once "database.php";
	require_once "user.php";
		
	class UserManager {
		static $user = null;
		static $realm = 'Forum';
		static $request_timeout = 30;
		
		static function logIn()
		{
			Database::connect();
			$response_data = self::getDigestHeader();
			$response = $response_data['response'];
			$user = User::getUserByName($response_data['username']);
			if($user)
			{
				$request_time_data = explode(' ', base64_decode($response_data['nonce']));
				$request_time = $request_time_data[0];
				if(isset($request_time) && time() - intval($request_time) < self::$request_timeout)
				{
					$A1 = $user->password;
					$A2 = md5($_SERVER['REQUEST_METHOD'].':'.$response_data['uri']);
					$valid_response = md5($A1.':'.$response_data['nonce'].':'.$response_data['nc'].':'.$response_data['cnonce'].':'.$response_data['qop'].':'.$A2);
					if($response == $valid_response)
					{
						self::$user = $user;
						return true;
					}
					else
						return false;
				}
				else
					header('408 Request Timeout');
			}
			else
				return false;
		}
		static function logOut()
		{
			unset(self::$user);
		}
		static function sendDigestHeader()
		{
			$nonce = base64_encode(time() . ' ' . md5(uniqid(rand(), true)));
			header('WWW-Authenticate: Digest realm="'.self::$realm.'",qop="auth",nonce="'.$nonce.'",opaque="'.md5(self::$realm).'"');
			header('HTTP/1.1 401 Unauthorized');
		}
		static function authenticate($force=false)
		{
			if($force || !empty($_SERVER['PHP_AUTH_DIGEST']))
			{
				if(empty($_SERVER['PHP_AUTH_DIGEST'])) {
					self::sendDigestHeader();
				}
				else {
					$header_data = self::getDigestHeader();
					if($header_data)
					{
						if(self::logIn()) {
						}
						else {
							self::sendDigestHeader();
						}
					}
					else
						header('HTTP/1.1 400 Bad Request');
				}
			}
			else
			{
			}
		}
		
		static function forceAuthenticate()
		{
			self::sendDigestHeader();
			$header_data = self::getDigestHeader();
			if($header_data)
			{
				if(UserManager::logIn($header_data)) {
				}
			}
		}
		
		static function getUser()
		{
			return isset(self::$user) ? self::$user : User::getUserByName('anonymous');
		}
		
		static function isUser()
		{
			return isset(self::$user);
		}
		
		static function isAdmin()
		{
			return self::getUser()->isAdmin();
		}
		
		static function getDigestHeader()
		{
			$header = $_SERVER['PHP_AUTH_DIGEST'];
			if(!$header) return false;
			$needed_parts = array('nonce'=>1, 'nc'=>1, 'cnonce'=>1, 'qop'=>1, 'username'=>1, 'uri'=>1, 'response'=>1);
			$data = array();
			preg_match_all('@(\w+)=([\'"]?)([a-zA-Z0-9=./\_-]+)\2@', $header, $matches, PREG_SET_ORDER);
			foreach ($matches as $m) {
				$data[$m[1]] = $m[3];
				unset($needed_parts[$m[1]]);
			}
			return $needed_parts ? false : $data;
		}
	}
	
	//TODO: Repair authentication via login link and remove the line below
	UserManager::authenticate(true);
?>
