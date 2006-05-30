<?php

class Box {

	function __construct($current)
	{
		?>
		<div id="box">
			<div class="logoDiv">Fuji</div>
			<div id="nav">
				<ul>
					<li <?php echo ($current == 1 ? 'class="current"' : ''); ?>>Step 1 (Database)</li>
					<li <?php echo ($current == 2 ? 'class="current"' : ''); ?>>Step 2 (Accounts)</li>			
				</ul>	
			</div>
		</div>
		
		<?php
	
	}
}

?>