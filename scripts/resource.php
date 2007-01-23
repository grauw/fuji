<?php

require_once 'template.php';

/**
 * Generic base class for HTTP resources
 * 
 * All URLs in Fuji map to one Resource. Typically such a resource has an URL which looks
 * something like resource/123 where ‘resource’ is the type of resource, and 123 is the ID
 * which uniquely identifies that resource.
 * 
 * On a HTTP resource, there are up to four methods available: GET, POST, PUT and DELETE.
 * Simply put, these correspond to the following operations:
 *  - show    : GET
 *  - create  : POST
 *  - update  : PUT
 *  - destroy : DELETE
 * 
 * This class lets the user implement up to four methods which are called by the HTTP methods.
 * Implement these interfaces as needed:
 *  - function httpGet();
 *  - function httpHead();
 *  - function httpPost();
 *  - function httpDelete();
 *  - function httpPut();
 * 
 * The class will automatically send the correct headers when a nonexistant method is called.
 * A message body with a application/x-www-form-urlencoded Content-Type will automatically be
 * made available in the $_POST hash array.
 * As for httpHead(), most implementations will want to forward it to httpGet(), PHP will
 * automatically abort the script once content is being sent.
 * 
 * There’s also a method to send the Vary header to indicate content-negotiation.
 *  - addVary();
 * 
 * For more details, please refer to the HTTP specification:
 *  - http://www.w3.org/Protocols/rfc2616/rfc2616.html
 * 
 * TODO: Should maybe be combined somehow with dataclass??
 * 
 */
class Resource {
	
	/**
	 * Run the proper request method, or return HTTP 405
	 */
	function __construct()
	{
		$method = $_SERVER['REQUEST_METHOD'];
		if ($method == 'GET' && method_exists($this,'httpGet')) {
			$this->httpGet();
		} elseif ($method == 'HEAD' && method_exists($this,'httpHead')) {
			$this->httpHead();
		} elseif ($method == 'POST' && method_exists($this,'httpPost')) {
			$this->httpPost();
		} elseif ($method == 'PUT' && method_exists($this,'httpPut')) {
			$this->prepPostDataFromPut();	// prep $_POST variable
			$this->httpPut();
		} elseif ($method == 'DELETE' && method_exists($this,'httpDelete')) {
			$this->httpDelete();
		} elseif ($method == 'OPTIONS' && method_exists($this,'httpPut')) {
			$this->httpOptions();
		} else {
			$this->httpMethodNotAllowed();
		}
	}
	
	/**
	 * Send response to OPTIONS header
	 */
	private function httpOptions() {
		$this->sendAllow();
		header('Content-Length: 0');
		header('HTTP/1.1 200 OK');
	}
	
	/**
	 * Send HTTP 405 Method Not Allowed response.
	 * (with automatic Allow header)
	 */
	private function httpMethodNotAllowed() {
		$this->sendAllow();
		header('HTTP/1.1 405 Method Not Allowed');
	}
	
	/**
	 * Send Allow header
	 */
	private function sendAllow() {
		$allow = array();
		if (method_exists($this, 'httpGet')) $allow[] = 'GET';
		if (method_exists($this, 'httpHead')) $allow[] = 'HEAD';
		if (method_exists($this, 'httpPost')) $allow[] = 'POST';
		if (method_exists($this, 'httpPut')) $allow[] = 'PUT';
		if (method_exists($this, 'httpDelete')) $allow[] = 'DELETE';
		if (method_exists($this, 'httpOptions')) $allow[] = 'OPTIONS';
		header('Allow: '.implode(', ', $allow));
	}
	
	/**
	 * Add a header type to the Vary collection
	 */
	private static $varying = array();
	
	public static function addVary($vary) {
		if (!in_array($vary,self::$varying)) {
			$varying[] = $vary;
			header('Vary: '.implode(', ', $varying));
		}
	}
	
	/**
	 * Fill in $_POST variable because it doesn’t happen automatically with PUT
	 */
	private function prepPostDataFromPut() {
		$putdata = file_get_contents('php://input');
		if ($_SERVER['CONTENT_TYPE'] == 'application/x-www-form-urlencoded') {
			$components = explode('&', $putdata);
			foreach ($components as $component) {
				$components2 = explode('=', $component);
				$key = urldecode($components2[0]);
				$value = urldecode($components2[1]);
				$_POST[$key] = $value;
			}
		} else header('HTTP/1.1 415 Unsupported Media Type');
	}
	
}	
	
?>