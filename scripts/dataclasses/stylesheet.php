<?php
require_once 'scripts/dataclass.php';
require_once 'scripts/database.php';

class Stylesheet extends Dataclass {
	
	public $default;
	
	static function getTableName() {
		return 'styles';
	}
	static function getPrimaryKeyName() {
		return 'name';
	}
	static function getResourceUriFormat() {
		return 'style/%s';
	}
	
	function __construct($data, $default = FALSE)
	{
		parent::__construct($data);
		$this->default = $default;
	}
	
	/**
	 * returns an array of Stylesheet-objects
	 * @returns Stylesheet
	 */
	static function getStylesheets($user=true)
	{
		$primarystyle = self::getDefaultStylesheetName($user);
		$stylesheets = array();
		$data = Database::getArray("SELECT * FROM " . DbVar::$db_tprefix . "styles");
		foreach ($data as $value) {
			array_push($stylesheets, new Stylesheet($value, $value['name']==$primarystyle));
		}
		return $stylesheets;
	}
	
	/**
	 * returns the default stylesheet
	 * @returns Stylesheet
	 */
	static function getDefaultStylesheet($user=true)
	{
		$primarystyle = self::getDefaultStylesheetName($user);
		$data = Database::getArray('SELECT * FROM ' . DbVar::$db_tprefix . 'styles WHERE name="' . $primarystyle . '"');
		return new Stylesheet($data[0], true);
	}
	
	/**
	 * returns the name of the default stylesheet
	 * @returns string
	 */
	static function getDefaultStylesheetName($user=true)
	{
		if ($user && isset(UserManager::getUser()->style)) {
			return UserManager::getUser()->style;
		} else {
			$default = Database::getArray("SELECT value FROM " . Database::$db_tprefix . "defaults WHERE name = 'style'");
			return $default[0][0];
		}
	}
	
	/**
	 * returns a Stylesheet-object given its id
	 * @param {String} $id Stylesheet to be returned
	 * @returns Stylesheet
	 */
	static function getStylesheet($id) {
		return new Stylesheet(array('id' => $id));
	}
	
	static function getStylesheetsHTML()
	{
		// save a lot of requests
		// comment out to get browser stylesheet selection
		return Stylesheet::getDefaultStylesheet()->getStylesheetHTML();
		
		$stylesheetsarr = Stylesheet::getStylesheets();
		$stylesheets = '';
		foreach ($stylesheetsarr as $value) {
			$stylesheets .= $value->getStylesheetHTML();
		}
		return $stylesheets;
	}
	
	static function getStylesheetsXML()
	{
		// save a lot of requests
		// comment out to get browser stylesheet selection
		return Stylesheet::getDefaultStylesheet()->getStylesheetXML();
		
		$stylesheetsarr = Stylesheet::getStylesheets();
		$stylesheets = '';
		foreach ($stylesheetsarr as $value) {
			$stylesheets .= $value->getStylesheetXML();
		}
		return $stylesheets;
	}
	
	function getStylesheetHTML()
	{
		return '<link rel="'.($this->default ? '' : 'alternate ').'stylesheet" type="text/css" href="'.$this->getForumRoot() . $this->file.'" title="'.$this->name.'" />'."\n"; 
	}
	
	function getStylesheetXML()
	{
		return '<?xml-stylesheet href="'.$this->getForumRoot() . $this->file.'" title="'.$this->name.'"'.($this->default ? '' : ' alternate="yes"').' type="text/css" ?'.">\n";
	}
}

?>