<?php	

require_once "database.php";

class StyleXML {
	
	private $doc = null;
	private $depth = 0;

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
			$path = "";
			$value = $elem->nodeValue;
			for($n = 0; n<$this->depth; $n++)
			{
				$path .= "../";
			}
			echo '<link rel="stylesheet" type="text/css" href="'.$path.'style/'.$value.'" />'; 
		}
	}
	
	function setDepth($depth)
	{
		$this->depth = $depth;
	}
}

?>