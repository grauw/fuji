<?php
	require_once("scripts/l10nmanager.php");
	require_once("scripts/usermanager.php");
	require_once("scripts/styleparser.php");
	UserManager::authenticate();
	L10nManager::loadL10n();
?>
<!DOCTYPE html 
     PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
     "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="<?php echo L10n::$language ?>" xml:lang="<?php echo L10n::$language ?>">
<head>
	<title>Fuji</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<?php StyleXML::getStyleXML()->getStyleSheets(); ?>
	<script type="text/javascript" src="js/yui/yahoo/yahoo.js"></script>
	<script type="text/javascript" src="js/yui/dom/dom.js"></script>
	<script type="text/javascript" src="js/yui/event/event.js"></script>
	<script type="text/javascript" src="js/yui/treeview/treeview.js"></script>
	<script type="text/javascript" src="js/yui/connection/connection.js"></script>
	<script type="text/javascript" src="js/yui/container/container.js"></script>
	<script type="text/javascript" src="js/yui/animation/animation.js"></script>
	<script type="text/javascript" src="js/yui/dragdrop/dragdrop.js"></script>
	<script type="text/javascript" src="js/fuji.js"></script>
</head>
<body onload="Fuji.init()">
	<a href="/"><h1 class="logoDiv logo window">Fuji</h1></a>
	<div id="foraDiv" class="window">
		<div id="foraTreeDiv"></div>
	</div>
	<div id="menuDiv" class="window">
		<h2 class="windowTitle"><?php echo L10n::$menu ?></h2>
		<div id="menuListDiv"></div>
	</div>
	<div id="threadsDiv" class="window">
	</div>
	<div id="postsDiv" class="window">
	</div>
	<div id="createDialog" style="display: none">
		<div id="createTitle" class="hd"><?php echo L10n::$new ?></div>
		<div id="createForm" class="bd">
			<form id="createF" name="createF" method="POST" action="item/">
				<input id='createRef' name='ref' type='hidden' value='' />
				<input id='createType' name='type' type='hidden' value='' />
				<?php echo L10n::$title ?>: <input id='createT' name='title' type='text' value='' /><br />
				<?php echo L10n::$message ?>: <textarea id='createContent' name='content' type='text' value=''></textarea>
			</form>
		</div>
	</div>
	<div id="editDialog" style="display: none">
		<div id="editTitle" class="hd"><?php echo L10n::$edit_thread ?></div>
		<div id="editForm" class="bd">
			<form id="editF" name="editF" method="POST" enctype="text/plain">
				<input id='editEntryid' name='entryid' type='hidden' value='' />
				<?php echo L10n::$title ?>: <input id='editT' name='title' type='text' value='' /><br />
				<?php echo L10n::$message ?>: <textarea id='editContent' name='content' type='text' value=''></textarea>
			</form>
		</div>
	</div>	
	<div id="prefDialog" style="display: none">
		<div id="prefTitle" class="hd"><?php echo L10n::$preferences ?></div>
		<div id="prefForm" class="bd">
			<form id="prefF" name="prefF" method="POST" action="preferences">
				<fieldset>
					<legend><?php echo L10n::$password ?></legend>
					<p><?php echo L10n::$enter_your_new_password ?>:<br />
					<input id='prefPass1' name='pass1' type='password' value='' />
					</p>
					<p><?php echo L10n::$reenter_your_new_password ?>:<br />
					<input id='prefPass2' name='pass2' type='password' value='' />
					</p>
				</fieldset>
				<fieldset>
					<legend><?php echo L10n::$lang ?></legend>
					<select id='prefLang' name='language'>
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
					<select id='prefStyle' name='style'>
						<?php
								$styles = StyleXML::getStyles();
								$s;
								if(isset(UserManager::getUser()->style))
									$s = UserManager::getUser()->style;
								foreach($styles as $style)
								{ 
									echo '<option value="' . $style['id'] . '"' . ($style['id'] == $s ? ' selected="Selected"' : '') .  '>' . $style['name'] . '</option>';
								}
						?>
					</select>
				</fieldset>				
				<fieldset>
					<legend><?php echo L10n::$profile ?></legend>
					<p><?php echo L10n::$email_address ?>:<br />
					<input id='prefEmail' name='email' type='text' value='<?php echo UserManager::getUser()->email ?>' />
					<input id='prefShowEmail' name='showEmail' type='checkbox' <?php if(UserManager::getUser()->email_visible) echo 'checked="Checked"' ?>><?php echo L10n::$visible_in_profile ?></input>
					</p>
					<p><?php echo L10n::$profile_photo ?> (URL):<br />
					<input id='prefPhoto' name='photo' type='text' value='<?php if(isset(UserManager::getUser()->photo)) echo UserManager::getUser()->photo ?>' />
					</p>
				</fieldset>
			</form>
		</div>
	</div>	
</body>
</html>
