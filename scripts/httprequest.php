<?php

abstract class HttpRequest {
	
	function __construct()
	{
		$method = $_SERVER['REQUEST_METHOD'];
		if ($method == 'GET') {
			$this->httpGet();
		} elseif ($method == 'POST') {
			$this->httpPost();
		} elseif ($method == 'DELETE') {
			$this->httpDelete();
		} elseif ($method == 'PUT') {
			$this->httpPut();
		} else {
			$this->httpMethodNotAllowed();
		}
	}
	
	/**
	 * Abstract implementations of HTTP methods
	 * (call httpMethodNotAllowed if not supported)
	 */
	abstract function httpGet();
	abstract function httpPost();
	abstract function httpDelete();
	abstract function httpPut();
	
	/**
	 * Send HTTP 405 Method Not Allowed response.
	 *
	 * Example:
	 *
	 * // 405 Method Not Allowed
	 * header('Allow: GET, POST, DELETE', true, '405');
	 */
	abstract function httpMethodNotAllowed();
	
}	
	
?>