<?php
require_once '../l10nmanager.php';
require_once '../stylesheet.php';
	class Register1 {
		function execute()
		{?>
			<table>
				<tr><td>Nickname</td><td><input name='nickname' type='text' /></td></tr>
				<tr><td>E-mail</td><td><input name='email' type='text' /></td></tr>
				<tr><td>Password</td><td><input name='password' type='password' /></td></tr>
				<tr>
					<td>Language</td>
					<td><select name='language'>
							<option value="Server default" selected="selected">Server Default</option>
						<?php	$l10ns = L10nManager::getL10ns();
								foreach($l10ns as $l10n)
								{ 
									echo '<option value="' . $l10n['id'] . '"' . '>' . $l10n['name'] . '</option>';
								}
						?>				
					</select></td>
				</tr>
				<tr>
				<td>Style</td>
					<td>
						<select name='style'>
							<option value="Server default" selected="selected">Server Default</option>
							<?php	$styles = Stylesheet::getStylesheets(false);
									foreach($styles as $style)
									{
										echo '<option value="' . $style->name . '">' . $style->name . '</option>';
									}
							?>
						</select>		
					</td>
				</tr>
			</table>
		<?php }
	}
?>
