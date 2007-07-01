<?php

class Search extends Plugin{
	public function name() { return 'search'; }
	public function description() { return 'Search plugin'; }
	public function author() { return ''; }
	public function homepage() { return 'http://fuji.sourceforge.net'; }
	public function version() { return '0.1'; }
	public function min_fuji_version() { return '0.0'; }
	public function max_fuji_version() { return '1.0'; }
	
	public function getMenu($template, $user, $type)
	{
		if($template == Plugin::$TEMPLATE_AJAX && $type == Plugin::$MENU_INDEX)
		{
			?><li><a href="#plugins/search"><?php echo $this->l10n->search ?></a></li><?php
		}
	}
	public function getJS()
	{
		?><script type="text/javascript" src="plugins/search/js/search.js"></script><?php
	}
}

?>