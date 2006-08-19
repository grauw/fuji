<?php
	class Statistics extends Plugin{
		public function getMenu($type)
		{
			if($type == Plugin::$MENU_INDEX)
			{
				//TODO: L10n for plugins
				?><li><a href="#plugins/statistics">Statistics</a></li><?php
			}
		}
	}
?>