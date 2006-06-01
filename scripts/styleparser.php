<?php	

require_once "database.php";

class StyleXML {
	
	private $doc = null;

	function __construct($xmldoc)
	{
		$this->doc = DOMDocument::load($xmldoc); 
	}
	
	static function getStyleXML()
	{
		$data = Database::getArray("SELECT * FROM " . Database::$db_tprefix . "styles WHERE isdefault = true");
		$selected = $data[0];
		return new styleXML("style/".$selected[2]);
	}
	
	function getStylesheets()
	{
		$elems = $this->doc->getElementsByTagName('css');
		foreach ($elems as $elem)
		{
			$value = $elem->nodeValue;
			echo '<link rel="stylesheet" type="text/css" href="style/'.$value.'" />'; 
		}
	}
}

?>