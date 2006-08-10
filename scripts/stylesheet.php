<?php	

require_once 'dataclass.php';
require_once "database.php";

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
		if ($user && isset(UserManager::getUser()->style)) {
			$primarystyle = UserManager::getUser()->style;
		} else {
			$default = Database::getArray("SELECT value FROM " . Database::$db_tprefix . "defaults WHERE name = 'style'");
			$primarystyle = $default[0][0];
		}
		$stylesheets = array();
		$data = Database::getArray("SELECT * FROM " . DbVar::$db_tprefix . "styles");
		foreach ($data as $value) {
			array_push($stylesheets, new Stylesheet($value, $value['name']==$primarystyle));
		}
		return $stylesheets;
	}
	
	/**
	 * returns a Stylesheet-object given its id
	 * @param {String} $id Stylesheet to be returned
	 * @returns Stylesheet
	 */
	static function getStylesheet($id) {
		return new Stylesheet(array('id' => $id));
	}
	
	static function getStylesheetsHTML($depth=0)
	{
		$stylesheetsarr = Stylesheet::getStylesheets();
		$stylesheets = '';
		foreach ($stylesheetsarr as $value) {
			$stylesheets .= $value->getStylesheetHTML($depth);
		}
		return $stylesheets;
	}
	
	static function getStylesheetsXML($depth=0)
	{
		$stylesheetsarr = Stylesheet::getStylesheets();
		$stylesheets = '';
		foreach ($stylesheetsarr as $value) {
			$stylesheets .= $value->getStylesheetXML($depth);
		}
		return $stylesheets;
	}
	
	function getStylesheetHTML($depth=0)
	{
		$rel_depth = '';
		for($n = 0; $n < $depth; $n++)
			$rel_depth .= '../';
		return '<link rel="'.($this->default ? '' : 'alternate ').'stylesheet" type="text/css" href="'.$rel_depth.$this->file.'" title="'.$this->name.'" />'."\n"; 
	}
	
	function getStylesheetXML($depth=0)
	{
		$rel_depth = '';
		for($n = 0; $n < $depth; $n++)
			$rel_depth .= '../';		
		return '<?xml-stylesheet href="'.$rel_depth.$this->file.'" title="'.$this->name.'"'.($this->default ? '' : ' alternate="yes"').' type="text/css" ?'.">\n";
	}
}

?>
