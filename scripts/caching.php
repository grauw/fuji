<?php

/**
 * This class takes care of sending and handling Last-Modified and ETag headers.
 * Note: when provided with both a modification date and an ETag, the modification date will also be inserted in the ETag.
 * 
 * Usage:
 * Return from a retrieval function on the cachable object, e.g.:
 *    return new Cache(filemtime('l10n/nl-NL.php'), 'nl-NL', FALSE);
 * Then on the returned value, call the send() function, e.g.:
 *    L10nManager::getL10nCache()->send();
 */
class Cache {
	
	private $last_modified;
	private $etag;
	private $weak_etag;
	
	/**
	 * Create a caching object based on last modified date and/or ETag.
	 * All parameters are optional (use FALSE for last modified)
	 * @param $last_modified Last modification date (UNIX timestamp).
	 * @param $etag Key identifying this cache version (string).
	 * @param $etag Set to TRUE if ETag key is 'weak' (see HTTP section 13.3.3).
	 */
	function __construct($last_modified = FALSE, $etag = FALSE, $weak_etag = FALSE) {
		$this->last_modified = $last_modified;
		$this->etag = $etag;
		$this->weak_etag = $weak_etag;
	}
	
	/**
	 * Send appropriate caching headers to the browser.
	 * (aborts script if caced)
	 */
	function send()
	{
		if ($this->last_modified !== FALSE) header('Last-Modified: '.$this->getFormattedLastModified());
		if ($this->etag !== FALSE)          header('ETag: '.$this->getFormattedETag());
		if (Cache::isCached()) {
			header('Not Modified', true, 304);
			exit();
		}
	}
	
	/**
	 * Test whether the given modification time and ETag match the browser's
	 * @returns TRUE if browser cache up-to-date
	 */
	function isCached()
	{
		$client_etag = $_SERVER['HTTP_IF_NONE_MATCH'] ? TRUE : FALSE;
		$client_last_modified = $_SERVER['HTTP_IF_MODIFIED_SINCE'] ? strtotime(trim($_SERVER['HTTP_IF_MODIFIED_SINCE'])) : FALSE;
		
		if ($client_last_modified && $client_etag) {
			return ($client_last_modified == $this->last_modified) && $this->strongETagComparison();
		} else if ($client_etag) {
			return $this->strongETagComparison();
		} else if ($client_last_modified) {
			return ($client_last_modified == $this->last_modified);
		} else {
			return FALSE;
		}
	}
	
	/**
	 * Combines two cache objects
	 * Takes most recent modification date, and concatenates ETags.
	 */
	function combine(Cache $cache) {
		if ($this->last_modified !== FALSE && $cache->last_modified !== FALSE) {
			$modified = max($this->last_modified, $cache->last_modified);
		} else if ($this->last_modified !== FALSE && $cache->last_modified === FALSE) {
			$modified = $this->last_modified;
		} else if ($this->last_modified === FALSE && $cache->last_modified !== FALSE) {
			$modified = $cache->last_modified;
		} else {
			$modified = FALSE;
		}
		$etag = $this->etag.'/'.$cache->etag;
		$weak_etag = $this->weak_etag || $cache->weak_etag ? TRUE : FALSE;
		return new Cache($modified, $etag, $weak_etag);
	}
	
	function getFormattedLastModified() {
		return gmdate('D, d M Y H:i:s T', $this->last_modified);
	}
	
	function getFormattedETag() {
		return ($this->weak_etag ? 'W/' : '').$this->getFormattedETagWithoutWeak();
	}
	
	function getFormattedETagWithoutWeak() {
		return '"'.$this->etag.($this->last_modified !== FALSE ? '/'.$this->last_modified : '').'"';
	}
	
	/**
	 * Strong ETag comparison
	 */
	function strongETagComparison() {
		if ($this->weak_etag) return FALSE;
		$formattedEtag = $this->getFormattedETag();
		preg_match_all('/\s*((?:W\/)?"[^"]*")\s*,?/', $_SERVER['HTTP_IF_NONE_MATCH'], $etags, PREG_SET_ORDER);
		foreach ($etags as $etag) {
			if ($etag[1] == $formattedEtag) return TRUE;
		}
		return FALSE;
	}
	
	/**
	 * Weak ETag comparison
	 * MAY only be done on a GET or HEAD request with If-None-Match.
	 */
	function weakETagComparison() {
		if (not($_SERVER['REQUEST_METHOD'] == 'GET' || $_SERVER['REQUEST_METHOD'] == 'HEAD')) return $this->strongETagComparison();
		$formattedEtag = $this->getFormattedETagWithoutWeak();
		preg_match_all('/\s*(?:W\/)?("[^"]*")\s*,?/', $_SERVER['HTTP_IF_NONE_MATCH'], $etags, PREG_SET_ORDER);
		foreach ($etags as $etag) {
			if ($etag[1] == $formattedEtag) return TRUE;
		}
		return FALSE;
	}
	
}

?>
