<?php

require_once 'template.php';

class Resource {
	
	// Implement these interfaces as needed:
	//
	// function httpGet();
	// function httpPost();
	// function httpDelete();
	// function httpPut();
	//
	// Functionality mappings:
	//
	// show    : GET
	// create  : POST
	// update  : PUT
	// destroy : DELETE
	
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
// TODO: check content type!
//		if (HttpMessage::getHeader('Content-Type') == 'application/x-www-form-urlencoded') {
			$components = explode('&', $putdata);
			foreach ($components as $component) {
				$components2 = explode('=', $component);
				$key = urldecode($components2[0]);
				$value = urldecode($components2[1]);
				$_POST[$key] = $value;
			}
//		} else header('HTTP/1.1 415 Unsupported Media Type');
	}
	
}	
	
?>