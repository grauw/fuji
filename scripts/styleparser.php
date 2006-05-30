<?php	

class StyleXML {
	
	$doc = null;

	__construct($xmldoc)
	{
		$this->doc = DOMDocument::load($xmldoc); 
	}
	
	function getStylesheets()
	{
		$elems = $doc->getElementsByTagName('css');
		foreach ($elems as $elem)
		{
			echo $elem; 
		}
	}
}

?>