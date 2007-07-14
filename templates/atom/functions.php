<?php
require_once 'scripts/dataclasses/template.php';

class Atom_Template extends Template {
	
	public function getPrefix()
	{
		if (!$this->acceptsAtom()) {
			header('Content-Type: text/xml');
		}
		return '<?xml version="1.0" encoding="UTF-8"?'.">\n";
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

?>