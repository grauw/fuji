<?php
	require_once "database.php";
	require_once "user.php";
		
	class UserManager {
		static $user = null;
		static $realm = 'Forum';
		
		/**
		 * Authenticate, in other words, determine which user is logged in.
		 * Authentication must be done before the headers are sent.
		 * @param force boolean Pass true to force the user to log in. Defaults to false.
		 */
		static function authenticate($force=false)
		{
			if ($force || self::hasDigestHeader()) {
				if (!self::hasDigestHeader()) {
					self::sendDigestHeader();
				} else {
					$header_data = self::getDigestHeader();
					if ($header_data) {
						if (self::logIn()) {
							// logged in
						} else {
							self::sendDigestHeader();
						}
					} else header('HTTP/1.1 400 Bad Request');
				}
			} else {
				self::logOut();
			}
		}
		
		/**
		 * Force the user to log in, regardless of whether he is already logged in.
		 * Authentication must be done before the headers are sent.
		 * @param force boolean Pass true to force the user to log in. Defaults to false.
		 */
		static function forceAuthenticate()
		{
			self::sendDigestHeader();
			$header_data = self::getDigestHeader();
			if ($header_data) {
				if (self::logIn($header_data)) {
				}
			}
		}
		
		/**
		 * Logs in the user based on the HTTP Digest authentication header
		 * @returns boolean false if login failed
		 */
		static private function logIn()
		{
			Database::connect();
			$response_data = self::getDigestHeader();
			$response = $response_data['response'];
			$user = User::getUserByName($response_data['username']);
			if ($user) {
				$A1 = $user->password;
				$A2 = md5($_SERVER['REQUEST_METHOD'].':'.$response_data['uri']);
				$valid_response = md5($A1.':'.$response_data['nonce'].':'.$response_data['nc'].':'.$response_data['cnonce'].':'.$response_data['qop'].':'.$A2);
				if ($response == $valid_response) {
					self::$user = $user;
					return true;
				}
			}
			return false;
		}
		
		/**
		 * Logs out the user (unsets the current user)
		 */
		static private function logOut()
		{
			self::$user = null;
		}
		
		/**
		 * Returns the current user
		 * @returns User the User object
		 */
		static function getUser()
		{
			return self::$user !== null ? self::$user : User::getUserByName('anonymous');
		}
		
		/**
		 * Returns whether a user is authenticated
		 */
		static function isUser()
		{
			return self::$user !== null;
		}
		
		/**
		 * Returns whether the current user is admin
		 */
		static function isAdmin()
		{
			return self::getUser()->isAdmin();
		}
		
		/**
		 * Sends a HTTP digest access authentication scheme header
		 */
		static private function sendDigestHeader()
		{
			$nonce = md5(time() . uniqid(rand(), true));
			header('WWW-Authenticate: Digest realm="'.self::$realm.'",qop="auth",nonce="'.$nonce.'",opaque="'.md5(self::$realm).'"');
			header('HTTP/1.1 401 Unauthorized');
		}
		
		/**
		 * Determines whether the client has sent an HTTP digest access authentication header.
		 */
		static private function hasDigestHeader() {
			return !empty($_SERVER['PHP_AUTH_DIGEST']);
		}
		
		/**
		 * Returns an array with the parts of a HTTP digest access authentication scheme header
		 */
		static private function getDigestHeader()
		{
			$header = $_SERVER['PHP_AUTH_DIGEST'];
			if (!$header) return false;
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
?>
