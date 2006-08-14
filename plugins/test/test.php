<?php
	class Test extends Plugin{
		public function getCSS()
		{
		}
		public function getJS()
		{
		}
		public function getMenu($type)
		{
		?><li><a href="javascript:alert('test')">Test</a></li><?php
		}
		public function getIndexExtension()
		{
		}
	}
?>