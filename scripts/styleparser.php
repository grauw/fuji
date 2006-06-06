<?php	

require_once "database.php";

class StyleXML {
	
	private $doc = null;
	private $path = '';

	function __construct($xmldoc, $depth=0)
	{
		$this->doc = DOMDocument::load($xmldoc);
		$this->path = self::getPath($depth); 		
	}
	
	static function getStyleXML($depth = 0)
	{	
		$data = Database::getArray("SELECT * FROM " . Database::$db_tprefix . "styles WHERE isdefault = true");
		$selected = $data[0];
		
		return new StyleXML(self::getPath($depth)."style/".$selected[2], $depth);
	}
	
	function getStylesheets()
	{
		$elems = $this->doc->getElementsByTagName('css');
		foreach ($elems as $elem)
		{
			$value = $elem->nodeValue;
			
			echo '<link rel="stylesheet" type="text/css" href="'.$this->path.'style/'.$value.'" />'; 
		}
	}
	
	static function getPath($depth)
	{
		$path = "";
		for($n = 0; $n<$depth; $n++)
		{
			$path .= "../";
		}
		return $path;
	}
	
	static function getStyles()
	{
	//waarvoor wordt deze gebruikt?
		return Database::getArray("SELECT * FROM " . Database::$db_tprefix . "styles ");
	}
}

?>
