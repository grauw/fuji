<?php
	require_once 'l10nmanager.php';
	class Statistics extends Plugin{
		public $name = 'statistics';
		public $description = 'Statistics plugin';
		public $author = '';
		public $homepage = 'http://fuji.sourceforge.net';
		public $min_version = '0.0';
		public $max_version = '1.0';
		
		public function __construct()
		{
			$this->l10n = new L10nManager(NULL, $this->name);
		}
		
		public function getMenu($type)
		{
			if($type == Plugin::$MENU_INDEX)
			{
				?><li><a href="#plugins/statistics"><?php echo $this->l10n->statistics ?></a></li><?php
			}
		}
	}
?>