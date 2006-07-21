<?php
require_once 'template.php';

class Atom_Template extends Template {
	
	public function getPrefix()
	{
		if (!$this->acceptsAtom()) {
			header('Content-Type: text/xml');
		}
	}
	
	public function getHead()
	{
	}
	
	public function getHeader()
	{
	}
	
	public function getFooter()
	{
	}
	
	public function acceptsAtom()
	{
		return strpos($_SERVER['HTTP_ACCEPT'],'application/atom+xml') ? true : false;
	}
	
}
	
return 'Atom_Template';

?>