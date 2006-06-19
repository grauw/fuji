<?php	

require_once 'dataclass.php';
require_once "database.php";

class Stylesheet extends Dataclass {
	
	private $default;
	
	static function getTable() { return 'styles'; }
	
	function __construct($data, $default = FALSE)
	{
		parent::__construct($data);
		$this->default = $default;
	}
	
	/**
	 * returns an array of Stylesheet-objects
	 * @returns Stylesheet
	 */
	static function getStylesheets()
	{
		if (isset(UserManager::getUser()->style)) {
			$primarystyle = UserManager::getUser()->style;
		} else {
			$default = Database::getArray("SELECT value FROM " . Database::$db_tprefix . "defaults WHERE name = 'style'");
			$primarystyle = $default[0][0];
		}
		$stylesheets = array();
		$data = Database::getArray("SELECT * FROM " . DbVar::$db_tprefix . "styles");
		foreach ($data as $value) {
			array_push($stylesheets, new Stylesheet($value, $value['id']==$primarystyle));
		}
		return $stylesheets;
	}
	
	/**
	 * returns a Stylesheet-object given its id
	 * @param {Int} $id Stylesheet to be returned
	 * @returns Stylesheet
	 */
	static function getStylesheet($id) {
		$data = Database::getArray("SELECT * FROM " . DbVar::$db_tprefix . "styles WHERE id = '$id'");
		$styledata = $data[0];
		if($styledata)
			return new Stylesheet($styledata);
	}
	
	static function getStylesheetsHTML()
	{
		$stylesheetsarr = Stylesheet::getStylesheets();
		$stylesheets = '';
		foreach ($stylesheetsarr as $value) {
			$stylesheets .= $value->getStylesheetHTML();
		}
		return $stylesheets;
	}
	
	static function getStylesheetsXML()
	{
		$stylesheetsarr = Stylesheet::getStylesheets();
		$stylesheets = '';
		foreach ($stylesheetsarr as $value) {
			$stylesheets .= $value->getStylesheetXML();
		}
		return $stylesheets;
	}
	
	function getStylesheetHTML()
	{
		return '<link rel="'.($this->default ? '' : 'alternate ').'stylesheet" type="text/css" href="'.$this->file.'" title="'.$this->name.'" />'."\n"; 
	}
	
	function getStylesheetXML()
	{
		return '<?xml-stylesheet href="'.$this->file.'" title="'.$this->name.'"'.($this->default ? '' : ' alternate="yes"').' type="text/css" ?'.">\n";
	}
	
	static function getStyles()
	{
	//waarvoor wordt deze gebruikt?
		return Database::getArray("SELECT * FROM " . Database::$db_tprefix . "styles ");
	}
}

?>
