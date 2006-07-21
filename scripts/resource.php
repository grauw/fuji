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
		} elseif ($method == 'POST' && method_exists($this,'httpPost')) {
			$this->httpPost();
		} elseif ($method == 'DELETE' && method_exists($this,'httpDelete')) {
			$this->httpDelete();
		} elseif ($method == 'PUT' && method_exists($this,'httpPut')) {
			//TODO: prep $_POST variable
			$this->httpPut();
		} else {
			$this->httpMethodNotAllowed();
		}
	}
	
	/**
	 * Send HTTP 405 Method Not Allowed response.
	 * (with automatic Allow header)
	 */
	private function httpMethodNotAllowed() {
		$allow = array();
		if (method_exists($this, 'httpGet')) $allow[] = 'GET';
		if (method_exists($this, 'httpPost')) $allow[] = 'POST';
		if (method_exists($this, 'httpDelete')) $allow[] = 'DELETE';
		if (method_exists($this, 'httpPut')) $allow[] = 'PUT';
		header('Allow: '.implode(', ', $allow), true, '405');
	}
	
	private static $varying = array();
	
	public static function addVary($vary) {
		if (!in_array($vary,self::$varying)) {
			$varying[] = $vary;
			header('Vary: '.implode(', ', $varying));
		}
	}
	
}	
	
?>