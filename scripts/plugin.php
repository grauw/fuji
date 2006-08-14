<?php
	abstract class Plugin {
		static public $MENU_INDEX = 0;
		static public $MENU_FORUMCONTAINER = 1;
		static public $MENU_THREADCONTAINER = 2;
		abstract public function getCSS();
		abstract public function getJS();
		abstract public function getMenu($type);
		abstract public function getIndexExtension();
	}
?>