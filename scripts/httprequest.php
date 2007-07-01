<?php

/**
 * This class contains methods to handle a HTTP request. It contains information about the request,
 * and delegates processing to the appropriate Resource class.
 * 
 * Historical note: this was previously done using a .htaccess file, but as the plugin architecture
 *                  was formed, it lacked extensibility. 
 * 
 * One of the functions is to act as a wrapper around the HTTP superglobals, to encourage good
 * programming practices and not rely on ‘superglobals’.
 * 
 * This class is inspired by Java’s HttpServletRequest. See:
 * http://java.sun.com/javaee/5/docs/api/javax/servlet/http/HttpServletRequest.html
 * http://java.sun.com/javaee/5/docs/api/javax/servlet/ServletRequest.html
 */
class HttpRequest {
	
	public $BASIC_AUTH = 1;
	public $DIGEST_AUTH = 2;
	public $COOKIE_AUTH = 3;
	public $routes;
	
	function __construct($routes)
	{
		$this->routes = $routes; 
	}
	
	/**
	 * Called after construction, when processing a request. The main method.
	 */
	function doRequest() {
		$uriparts = $this->getRequestURIParts();
		
		$resource = $this->followRoutingPath($this->routes, $uriparts);
		
		if ($resource != '') {
			require_once 'scripts/resources/'.strtolower($resource).'.php';
			$bla = new $resource($this);
		} else {
			header('HTTP/1.1 404 Not Found');
			header('Content-Type: text/plain');
			echo '404 Not Found';
		}
	}
	
	private function followRoutingPath($route, $pathparts) {
		if (is_array($route)) {
			$part = array_shift($pathparts);
			if ($part === NULL)
				$part = '';
			if (array_key_exists($part, $route)) {
				// if directly found in array
				return $this->followRoutingPath($route[$part], $pathparts);
			} else {
				// else check if there’s a regular expression in the array that matches
				foreach ($route as $key => $value) {
					if ($key[0] == '/' && preg_match($key, $part, $matches)) {
						$resource = $this->followRoutingPath($value, $pathparts);
						//$matches[0];
						return $resource;
					}
				}
			}
		} elseif (count($pathparts) == 0 || (count($pathparts) == 1 && $pathparts[0] == '')) {
			return $route;
		}
		return '';
	}
	
	/**
	 * Returns the request’s method.
	 * Allows you to spoof the method by doing a POST request with a _method parameter.
	 * (This is a workaround for browsers which only allow forms to use GET and POST.)
	 * 
	 * @return The request method. Usually either GET, POST, PUT or DELETE.
	 */
	function getMethod() {
		return isset($_POST['_method']) ? isset($_POST['_method']) : $_SERVER['REQUEST_METHOD'];
	}
	
	/**
	 * Returns an array with all set cookies.
	 * 
	 * @return Associative array with cookies as key/value pairs.
	 */
	function getCookies() {
		return $_COOKIE;
	}
	
	/**
	 * Returns the part of this request's URL from the protocol name up to the query string
	 * in the first line of the HTTP request. The URI is not decoded.
	 */
	function getRequestURI() {
		return $_SERVER['REDIRECT_URL'];
	}
	
	/**
	 * Returns the request URI with any extensions removed.
	 */
	function getRequestURIWithoutExtension() {
		$uri = $this->getRequestURI();
		$extpos = strrpos($uri, '.');
		return $extpos === FALSE ? $uri : substr($uri, 0, $extpos);
	}
	
	/**
	 * Returns the parts of the request URI (separated by /’s).
	 */
	function getRequestURIParts() {
		return explode('/', substr($this->getRequestURIWithoutExtension(), 1));
	}
	
	/**
	 * Reconstructs the URL the client used to make the request.
	 * The returned URL contains a protocol, server name, port number, and server path,
	 * but it does not include query string parameters.
	 */
	function getRequestURL() {
		return 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	}
	
	/**
	 * Returns the request’s query string.
	 * The query string is the part after the ?.
	 */
	function getQueryString() {
		return $_SERVER['REDIRECT_QUERY_STRING'];
	}
	
	/**
	 * Returns the value of the header with the specified name.
	 */
	function getHeader($header) {
		return $_SERVER['HTTP_'.strtoupper(strtr($header, '-', '_'))];
	}
	
	/**
	 * Returns a boolean indicating whether this request was made using a secure channel, such as HTTPS.
	 */
	function isSecure() {
		return $_SERVER['HTTPS'] != '';
	}
	
	/**
	 * Returns the port number to which the request was sent.
	 */
	function getServerPort() {
		return $_SERVER['SERVER_PORT'];
	}
	
	/**
	 * Returns the host name of the server to which the request was sent.
	 */
	function getServerHost() {
		if ($_SERVER['HTTP_HOST']) {
			$hostname = explode(':',$_SERVER['HTTP_HOST']);
			return $hostname[0];
		} else {
			$_SERVER['SERVER_ADDR'];
		}
	}
	
	/**
	 * Returns the name and version of the protocol the request uses in the form
	 * protocol/majorVersion.minorVersion, for example, HTTP/1.1.
	 */
	function getProtocol() {
		return $_SERVER['SERVER_PROTOCOL'];
	}
	
	/**
	 * Returns the Internet Protocol (IP) address of the client or last proxy that sent the request.
	 */
	function getRemoteAddr() {
		return $_SERVER['REMOTE_ADDR'];
	}
	
	/**
	 * Returns the fully qualified name of the client or the last proxy that sent the request.
	 */
	function getRemoteHost() {
		return $_SERVER['REMOTE_HOST'];
	}
	
	/**
	 * Returns the Internet Protocol (IP) source port of the client or last proxy that sent the request.
	 */
	function getRemotePort() {
		return $_SERVER['REMOTE_PORT'];
	}
	
	/**
	 * Retrieves the Accept header.
	 * If an extension is present, forces the MIME type accordingly.
	 * 
	 * @return The HTTP request’s Accept header value
	 */
	function getAccept() {
		$ext = $this->getExtension();
		if ($ext === FALSE) {
			return $_SERVER['HTTP_ACCEPT'];
		} else {
			switch ($ext) {
			case 'rss':
				return 'application/rss+xml';
			case 'atom':
				return 'application/atom+xml';
			case 'wml':
				return 'text/vnd.wap.wml';
			case 'xhtml':
				return 'application/xhtml+xml';
			case 'rdf':
				return 'application/rdf+xml';
			case 'fuji':
				return 'application/x-fuji+xhtml+xml';
			default:
				return $_SERVER['HTTP_ACCEPT'];
			}
		}
	}
	
	/**
	 * Retrieves the URI’s extension.
	 * Used by MIME-type forcing, and also to append to links from within MIME-type-forced pages.
	 * 
 	 * @return The extension, or FALSE if there is no extension.
	 */
	function getExtension() {
		$uri = $this->getRequestURI();
		$extpos = strrpos($uri, '.'); 
		if ($extpos === FALSE) return FALSE;
		return substr($uri, $extpos + 1);
	}
	
}

?>