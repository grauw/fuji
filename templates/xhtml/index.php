<?php
if(file_exists("../scripts/configuration/installer_disabled"))
{
	require_once("../scripts/usermanager.php");	
	UserManager::authenticate();
	echo $this->getPrefix();
?>
<!DOCTYPE html 
     PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
     "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="<?php echo L10n::$language ?>" xml:lang="<?php echo L10n::$language ?>">
<head>
	<title>Fuji</title>
	<?php echo $this->getHead() ?>
	<link id="rsslink" rel="alternate" type="application/rss+xml" href="item.rss" />
	<link id="atomlink" rel="alternate" type="application/atom+xml" href="item.atom" />
	<script type="text/javascript" src="js/yui/yahoo/yahoo-min.js"></script>
	<script type="text/javascript" src="js/yui/dom/dom-min.js"></script>
	<script type="text/javascript" src="js/yui/event/event-min.js"></script>
	<script type="text/javascript" src="js/yui/connection/connection-min.js"></script>
	<script type="text/javascript" src="js/yui/container/container-min.js"></script>
	<script type="text/javascript" src="js/yui/animation/animation-min.js"></script>
	<script type="text/javascript" src="js/yui/dragdrop/dragdrop-min.js"></script>
	<script type="text/javascript" src="js/fuji.js"></script>
	<script type="text/javascript" src="js/l10n.js.php"></script>
	<script type="text/javascript" src="js/dialogs.js"></script>
	<script type="text/javascript">
		Fuji.settings = { visual_effects : <?php echo UserManager::getUser()->visual_effects == '1' ? 'true' : 'false' ?> }
	</script>
</head>
<body onload="Fuji.init()">
	<h1 class="logoDiv logo window"><a href="http://fuji.sourceforge.net">Fuji</a></h1>
	<div id="menuDiv" class="menuContainer box">
		<h2 class="windowTitle"><?php echo L10n::$menu ?></h2>
		<div id="menuListDiv"></div>
	</div>
	<div id="foraDiv" class="forumListContainer box">
	</div>
	<div id="threadsDiv">
	</div>
	<div id="createDialog" style="display: none">
		<div id="createTitle" class="hd"><?php echo L10n::$new ?></div>
		<div id="createForm" class="bd">
			<form id="createF" name="createF" class="createEditF" method="POST" action="item">
				<input id='createType' name='type' type='hidden' value='' />
				<div><span><?php echo L10n::$title ?></span><input id='createT' class="createEditT" name='title' type='text' value='' /></div>
				<div>	<img alt="Bold" src="style/default/graphics/button-bold.png" onclick="Fuji.addTags(document.getElementById('createCtent'), '[b][/b]')" />
						<img alt="Italic" src="style/default/graphics/button-italic.png" onclick="Fuji.addTags(document.getElementById('createCtent'), '[i][/i]')" />
						<img alt="Underline" src="style/default/graphics/button-underline.png" onclick="Fuji.addTags(document.getElementById('createCtent'), '[u][/u]')" />
						<img alt="Code" src="style/default/graphics/button-code.png" onclick="Fuji.addTags(document.getElementById('createCtent'), '[code][/code]')" />
						<img alt="Quote" src="style/default/graphics/button-quote.png" onclick="Fuji.addTags(document.getElementById('createCtent'), '[quote][/quote]')" />
						<img alt="URL" src="style/default/graphics/button-url.png" onclick="Fuji.addTags(document.getElementById('createCtent'), '[url][/url]')" />
						<img alt="Image" src="style/default/graphics/button-image.png" onclick="Fuji.addTags(document.getElementById('createCtent'), '[img][/img]')" />
				</div>
				<div><textarea id='createCtent' class="createEditContent" name='content' type='text' value=''></textarea></div>
			</form>
		</div>
	</div>
	<div id="editDialog" style="display: none">
		<div id="editTitle" class="hd"><?php echo L10n::$edit ?></div>
		<div id="editForm" class="bd">
			<form id="editF" name="editF" class="createEditF" method="PUT">
				<div><span><?php echo L10n::$title ?></span><input id='editT' class="createEditT" name='title' type='text' value='' /></div>
				<div>	<img alt="Bold" src="style/default/graphics/button-bold.png" onclick="Fuji.addTags(document.getElementById('editContent'), '[b][/b]')" />
						<img alt="Italic" src="style/default/graphics/button-italic.png" onclick="Fuji.addTags(document.getElementById('editContent'), '[i][/i]')" />
						<img alt="Underline" src="style/default/graphics/button-underline.png" onclick="Fuji.addTags(document.getElementById('editContent'), '[u][/u]')" />
						<img alt="Code" src="style/default/graphics/button-code.png" onclick="Fuji.addTags(document.getElementById('editContent'), '[code][/code]')" />
						<img alt="Quote" src="style/default/graphics/button-quote.png" onclick="Fuji.addTags(document.getElementById('editContent'), '[quote][/quote]')" />
						<img alt="URL" src="style/default/graphics/button-url.png" onclick="Fuji.addTags(document.getElementById('editContent'), '[url][/url]')" />
						<img alt="Image" src="style/default/graphics/button-image.png" onclick="Fuji.addTags(document.getElementById('editContent'), '[img][/img]')" />
				</div>
				<div><textarea id='editContent' class="createEditContent" name='content' type='text' value=''></textarea></div>
			</form>
		</div>
	</div>	
	<div id="newGroupDialog" style="display: none">
		<div id="newGroupTitle" class="hd"><?php echo L10n::$new_group ?></div>
		<div id="newGroupForm" class="bd">
			<form id="newGroupF" name="newGroupF" class="createEditF" method="POST" action="groups/">
				<label><?php echo L10n::$name ?><input id="newGroupName" name='groupName' type="text" value='' /></label><br />
				<label><input type="checkbox" name="newGroupRead" id="newGroupRead" /><?php echo L10n::$read ?></label><br />
				<label><input type="checkbox" name="newGroupPost" id="newGroupPost" /><?php echo L10n::$post ?></label><br />
				<label><input type="checkbox" name="newGroupEdit" id="newGroupEdit" /><?php echo L10n::$edit ?></label><br />
				<label><input type="checkbox" name="newGroupDelete" id="newGroupDelete" /><?php echo L10n::$delete ?></label><br />
			</form>
		</div>
	</div>
	<div id="editGroupDialog" style="display: none">
		<div id="editGroupTitle" class="hd"><?php echo L10n::$edit_group ?></div>
		<div id="editGroupForm" class="bd">
			<form id="editGroupF" name="editGroupF" class="createEditF" method="POST" action="groups/*">
				<label><input type="checkbox" name="editGroupRead" id="editGroupRead" /><?php echo L10n::$read ?></label><br />
				<label><input type="checkbox" name="editGroupPost" id="editGroupPost" /><?php echo L10n::$post ?></label><br />
				<label><input type="checkbox" name="editGroupEdit" id="editGroupEdit" /><?php echo L10n::$edit ?></label><br />
				<label><input type="checkbox" name="editGroupDelete" id="editGroupDelete" /><?php echo L10n::$delete ?></label><br />
			</form>
		</div>
	</div>
	<div id="configRightsDialog" style="display: none">
	</div>
	<?php if(UserManager::getUser()->isAdmin()) { ?>
	<div id="serverSettingsDialog" style="display: none">
		<div class="hd"><?php echo L10n::$preferences ?></div>
		<div class="bd">
			<form name="serverSettingsF" method="POST" action="serversettings">
				<p><?php echo L10n::$default_language ?><br />
				<select name='default_language'>
					<?php
							$l10ns = L10nManager::getL10ns();
							$lang = L10nManager::getDefaultL10n();
							foreach($l10ns as $l10n)
							{ 
								echo '<option value="' . $l10n['id'] . '"' . ($l10n['id'] == $lang ? ' selected="Selected"' : '') .  '>' . $l10n['name'] . '</option>';
							}
					?>
				</select>
				</p>
				<p><?php echo L10n::$default_style ?><br />
				<select name='default_style'>
					<?php
							$styles = Stylesheet::getStylesheets();
							foreach($styles as $style)
							{
								echo '<option value="' . $style->name . '"' . ($style->default ? ' selected="Selected"' : '') .  '>' . $style->name . '</option>';
							}
					?>
				</select>
				</p>
				<p><?php echo L10n::$gzip_compression ?><br />
				<select name='gzip'>
					<option value="0">0 (<?php echo L10n::$least_cpu_load ?>)</option>
					<option value="1">1</option>
					<option value="2">2</option>
					<option value="3">3</option>
					<option value="4">4</option>
					<option value="5">5</option>
					<option value="6" selected="Selected">6</option>
					<option value="7">7</option>
					<option value="8">8</option>
					<option value="9">9 (<?php echo L10n::$least_network_traffic ?>)</option>
				</select>
				</p>				
			</form>
		</div>
	</div>
	<?php } ?>			
	<div id="prefDialog" style="display: none">
		<div class="hd"><?php echo L10n::$preferences ?></div>
		<div class="bd">
			<form name="prefF" method="POST" action="preferences">
				<fieldset>
					<legend><?php echo L10n::$password ?></legend>
					<p><?php echo L10n::$enter_your_new_password ?><br />
					<input id='prefPass1' name='pass1' type='password' value='' />
					</p>
					<p><?php echo L10n::$reenter_your_new_password ?><br />
					<input id='prefPass2' name='pass2' type='password' value='' />
					</p>
				</fieldset>
				<fieldset>
					<legend><?php echo L10n::$lang ?></legend>
					<select name='language'>
						<?php
								$l10ns = L10nManager::getL10ns();
								$lang;
								if(isset(UserManager::getUser()->language))
									$lang = UserManager::getUser()->language;
								foreach($l10ns as $l10n)
								{ 
									echo '<option value="' . $l10n['id'] . '"' . ($l10n['id'] == $lang ? ' selected="Selected"' : '') .  '>' . $l10n['name'] . '</option>';
								}
						?>
					</select>
				</fieldset>
				<fieldset>
					<legend><?php echo L10n::$style ?></legend>
					<select name='style'>
						<?php
								$styles = Stylesheet::getStylesheets();
								$s;
								if(isset(UserManager::getUser()->style))
									$s = UserManager::getUser()->style;
								else
									$s = '';
								foreach($styles as $style)
								{ 
									echo '<option value="' . $style->name . '"' . (($style->name == $s) || ($s == '' && $style->name == $style->default) ? ' selected="Selected"' : '') .  '>' . $style->name . '</option>';
								}
						?>
					</select>
					<input name='visualEffects' type='checkbox' <?php if(UserManager::getUser()->visual_effects) echo 'checked="Checked"' ?> /><?php echo L10n::$visual_effects ?>
				</fieldset>				
				<fieldset>
					<legend><?php echo L10n::$profile ?></legend>
					<p><?php echo L10n::$email_address ?><br />
					<input id='prefEmail' name='email' type='text' value='<?php echo UserManager::getUser()->email ?>' />
					<input id='prefShowEmail' name='showEmail' type='checkbox' <?php if(UserManager::getUser()->email_visible) echo 'checked="Checked"' ?> /><?php echo L10n::$visible_in_profile ?>
					</p>
					<p><?php echo L10n::$profile_photo ?><br />
					<input id='prefPhoto' name='picture' type='text' value='<?php if(isset(UserManager::getUser()->picture)) echo UserManager::getUser()->picture ?>' />
					</p>
				</fieldset>
			</form>
		</div>
	</div>	
</body>
</html>
<?php } else { ?>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en:GB" xml:lang="en:GB">
<head>
	<title>Fuji</title>
	<?php echo $this->getHead() ?>
</head>
<body>
	<p>Your Fuji Forum has to be configured before you can start using it.</p>
	<p>Please run the <a href="install">installer</a> first.</p>
</body>
</html>
<?php } ?>
