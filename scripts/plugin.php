<?php
require_once 'scripts/l10nmanager.php';

abstract class Plugin {
	static public $MENU_INDEX = 0;
	static public $MENU_FORUMCONTAINER = 1;
	static public $MENU_THREADCONTAINER = 2;
	static public $INDEX_PAGECONTENT = 0;
	static public $INDEX_DIALOGS = 1;
	static public $USER_ANONYMOUS = 0;
	static public $USER_LOGGED_IN = 1;
	static public $USER_ADMINISTRATOR = 2;
	static public $TEMPLATE_AJAX = 0;
	static public $TEMPLATE_XHTML = 1; //not used yet
	static public $TEMPLATE_ATOM = 2; //not used yet
	static public $TEMPLATE_RSS = 3; //not used yet
	static public $TEMPLATE_WML = 4; //not used yet
	abstract public function name();
	abstract public function description();
	abstract public function author();
	abstract public function homepage();
	abstract public function version();
	abstract public function min_fuji_version();
	abstract public function max_fuji_version();
	
	public function __construct()
	{
		$this->l10n = new L10nManager(NULL, $this->name());
	}
}

?>