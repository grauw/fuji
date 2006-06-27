<?php

class HttpRequest {
	
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
			$this->httpPut();
		} else {
			$this->httpMethodNotAllowed();
		}
	}
	
	// Implement these interfaces as needed:
	//
	// function httpGet();
	// function httpPost();
	// function httpDelete();
	// function httpPut();
	
	/**
	 * Send HTTP 405 Method Not Allowed response.
	 * (with automatic Allow header)
	 */
	private function httpMethodNotAllowed() {
		$allow = array();
		if (method_exists($this, 'httpGet')) array_push($allow, 'GET');
		if (method_exists($this, 'httpPost')) array_push($allow, 'POST');
		if (method_exists($this, 'httpDelete')) array_push($allow, 'DELETE');
		if (method_exists($this, 'httpPut')) array_push($allow, 'PUT');
		header('Allow: '.implode(', ', $allow), true, '405');
	}
	
}	
	
?>