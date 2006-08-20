<?php
	class Statistics extends Plugin{
		public function name() { return 'statistics'; }
		public function description() { return 'Statistics plugin'; }
		public function author() { return ''; }
		public function homepage() { return 'http://fuji.sourceforge.net'; }
		public function min_version() { return '0.0'; }
		public function max_version() { return '1.0'; }
		
		public function getMenu($template, $user, $type)
		{
			if($template == Plugin::$TEMPLATE_AJAX && $user == Plugin::$USER_LOGGED_IN && $type == Plugin::$MENU_INDEX)
			{
				?><li><a href="#plugins/statistics"><?php echo $this->l10n->statistics ?></a></li><?php
			}
		}
	}
?>