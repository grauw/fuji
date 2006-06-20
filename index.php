<?php
	require_once("scripts/l10nmanager.php");
	require_once("scripts/usermanager.php");
	require_once("scripts/xhtml.php");
	UserManager::authenticate();
	L10nManager::loadL10n();
	echo XHTML::pageHeaders();
?>
<!DOCTYPE html 
     PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
     "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="<?php echo L10n::$language ?>" xml:lang="<?php echo L10n::$language ?>">
<head>
	<title>Fuji</title>
	<?php echo XHTML::headContent(); ?>
	<link id="rsslink" rel="alternate" type="application/rss+xml" href="rss/" />
	<link id="atomlink" rel="alternate" type="application/atom+xml" href="atom/" />
	<script type="text/javascript" src="js/yui/yahoo/yahoo.js"></script>
	<script type="text/javascript" src="js/yui/dom/dom.js"></script>
	<script type="text/javascript" src="js/yui/event/event.js"></script>
	<script type="text/javascript" src="js/yui/treeview/treeview.js"></script>
	<script type="text/javascript" src="js/yui/connection/connection.js"></script>
	<script type="text/javascript" src="js/yui/container/container.js"></script>
	<script type="text/javascript" src="js/yui/animation/animation.js"></script>
	<script type="text/javascript" src="js/yui/dragdrop/dragdrop.js"></script>
	<script type="text/javascript" src="js/fuji.js"></script>
	<script type="text/javascript">Fuji.messages = 	{ 	warning							: '<?php echo L10n::$warning ?>',
														error							: '<?php echo L10n::$error ?>',
														sure_delete_item				: '<?php echo L10n::$sure_delete_item ?>',
														passwords_do_not_match			: '<?php echo L10n::$passwords_do_not_match ?>',
														email_empty						: '<?php echo L10n::$email_empty ?>',
														invalid_url						: '<?php echo L10n::$invalid_url ?>',
														ok								: '<?php echo L10n::$ok ?>',
														cancel							: '<?php echo L10n::$cancel ?>',
														error_insufficient_rights		: '<?php echo L10n::$error_insufficient_rights ?>',
														possible_reasons				: '<?php echo L10n::$possible_reasons ?>',
														error_restricted_forum			: '<?php echo L10n::$error_restricted_forum ?>',
														error_rights_revoked			: '<?php echo L10n::$error_rights_revoked ?>',
														error_anonymous					: '<?php echo L10n::$error_anonymous ?>',
														error_deleted_resource			: '<?php echo L10n::$error_deleted_resource ?>',
														error_server_misconfiguration	: '<?php echo L10n::$error_server_misconfiguration ?>',
														error_resource_cannot_be_found	: '<?php echo L10n::$error_resource_cannot_be_found ?>',
														error_invalid_url				: '<?php echo L10n::$error_invalid_url ?>',
														error_unknown					: '<?php echo L10n::$error_unknown ?>',
														error_bug						: '<?php echo L10n::$error_bug ?>',
														contact_administrator			: '<?php echo L10n::$contact_administrator ?>',
														error_loading_resource			: '<?php echo L10n::$error_loading_resource ?>',
														loading							: '<?php echo L10n::$loading ?>',
														please_wait						: '<?php echo L10n::$please_wait ?>'
													}
	</script>
</head>
<body onload="Fuji.init()">
	<h1 class="logoDiv logo window"><a href="http://fuji.sourceforge.net">Fuji</a></h1>
	<div id="foraDiv">
		<div id="foraTreeDiv"></div>
	</div>
	<div id="menuDiv">
		<h2 class="windowTitle"><?php echo L10n::$menu ?></h2>
		<div id="menuListDiv"></div>
	</div>
	<div id="threadsDiv">
	</div>
	<div id="createDialog" style="display: none">
		<div id="createTitle" class="hd"><?php echo L10n::$new ?></div>
		<div id="createForm" class="bd">
			<form id="createF" name="createF" class="createEditF" method="POST" action="item/">
				<input id='createRef' name='ref' type='hidden' value='' />
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
			<form id="editF" name="editF" class="createEditF" method="POST" enctype="text/plain">
				<input id='editEntryid' name='entryid' type='hidden' value='' />
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
			<form id="editGroupF" name="editGroupF" class="createEditF" method="POST" action="groups/">
				<label><input type="checkbox" name="editGroupRead" id="editGroupRead" /><?php echo L10n::$read ?></label><br />
				<label><input type="checkbox" name="editGroupPost" id="editGroupPost" /><?php echo L10n::$post ?></label><br />
				<label><input type="checkbox" name="editGroupEdit" id="editGroupEdit" /><?php echo L10n::$edit ?></label><br />
				<label><input type="checkbox" name="editGroupDelete" id="editGroupDelete" /><?php echo L10n::$delete ?></label><br />
				<input type="hidden" name="editGroupId" id="editGroupId" />
			</form>
		</div>
	</div>
	<div id="configRightsDialog" style="display: none">
	</div>
	<div id="prefDialog" style="display: none">
		<div id="prefTitle" class="hd"><?php echo L10n::$preferences ?></div>
		<div id="prefForm" class="bd">
			<form id="prefF" name="prefF" method="POST" action="preferences">
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
								$styles = Stylesheet::getStylesheets();
								$s;
								if(isset(UserManager::getUser()->style))
									$s = UserManager::getUser()->style;
								else
									$s = Stylesheet::getDefaultStyle();
								foreach($styles as $style)
								{ 
									echo '<option value="' . $style->name . '"' . ($style->name == $s ? ' selected="Selected"' : '') .  '>' . $style->name . '</option>';
								}
						?>
					</select>
				</fieldset>				
				<fieldset>
					<legend><?php echo L10n::$profile ?></legend>
					<p><?php echo L10n::$email_address ?><br />
					<input id='prefEmail' name='email' type='text' value='<?php echo UserManager::getUser()->email ?>' />
					<input id='prefShowEmail' name='showEmail' type='checkbox' <?php if(UserManager::getUser()->email_visible) echo 'checked="Checked"' ?>><?php echo L10n::$visible_in_profile ?></input>
					</p>
					<p><?php echo L10n::$profile_photo ?><br />
					<input id='prefPhoto' name='photo' type='text' value='<?php if(isset(UserManager::getUser()->photo)) echo UserManager::getUser()->photo ?>' />
					</p>
				</fieldset>
			</form>
		</div>
	</div>	
</body>
</html>
