<?php	

require_once "database.php";

class StyleXML {
	
	private $doc = null;

	__construct($xmldoc)
	{
		$this->doc = DOMDocument::load($xmldoc); 
	}
	
	static function getStyleXML()
	{
		Database::getArray("SELECT * FROM " . Database::$db_tprefix . "styles WHERE isdefault = true");
		
		//etcetera 
		return new styleXML();
	}
	
	function getStylesheets()
	{
		$elems = $doc->getElementsByTagName('css');
		foreach ($elems as $elem)
		{
			echo '<link rel="stylesheet" type="text/css" href="'.$elem.'" />'; 
		}
	}
}

?>