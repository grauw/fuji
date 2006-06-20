<?php
include_once '../adodb_lite/adodb.inc.php';
include_once '../adodb_lite/adodb-exceptions.inc.php'; 

class Install3 {   
    
	function execute()
	{
		require_once 'database.php';
		require_once '../l10nmanager.php';
		require_once '../stylesheet.php';
		if(isset($_POST['language']))
		{
			$this->configDefaultOptions();
			$this->showSuccess();
		}
		else
			$this->showPage();
	}
	
	function showPage() {
	?>
		<table>
			<tr>
				<td>Default language</td>
				<td><select name='language'>
						<?php	$l10ns = L10nManager::getL10ns();
								foreach($l10ns as $l10n)
								{ 
									echo '<option value="' . $l10n['id'] . '"' . '>' . $l10n['name'] . '</option>';
								}
						?>				
					</select>
				</td>
			</tr>
			<tr>
				<td>Default style</td>
				<td>
					<select name='style'>
						<?php	$styles = Stylesheet::getStyles();
								foreach($styles as $style)
								{
									echo '<option value="' . $style['name'] . '"' . ($style['name'] == 'Default Fuji' ? 'selected="Selected"' : '') . '>' . $style['name'] . '</option>';
								}
						?>
					</select>		
				</td>
			</tr>
			<tr>
				<td>GZIP compression</td>
				<td>
					<select name='gzip'>
						<option value="0">0 (least cpu-load)</option>
						<option value="1">1</option>
						<option value="2">2</option>
						<option value="3">3</option>
						<option value="4">4</option>
						<option value="5">5</option>
						<option value="6" selected="Selected">6</option>
						<option value="7">7</option>
						<option value="8">8</option>
						<option value="9">9 (least network-traffic)</option>
					</select>
				</td>
			</tr>
		</table>
	<?php global $target_phase, $current_phase;
			  $target_phase = $current_phase;
	}
	
	function showSuccess() {
		echo "Your settings were stored successfully";
	}
	
	function configDefaultOptions() {
		L10nManager::manualLoadL10n($_POST['language']);
		$idb = new InstallDb();
		$idb->addDefaultLanguage($_POST['language']);
		$idb->addDefaultStyle($_POST['style']);
		$idb->addDefaultGZIP($_POST['gzip']);
		$idb->addWelcomeMessage(L10n::$welcome, L10n::$welcome_message);				
	}
}

?>