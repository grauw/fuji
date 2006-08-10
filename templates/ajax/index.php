<?xml version="1.0" encoding="UTF-8"?>
<fuji:item xmlns="http://www.w3.org/1999/xhtml"
	xmlns:fuji="http://fuji.sourceforge.net/ns"
	xml:lang="<?php echo $this->l10n->language ?>"
	loadcontext="fujiBody">
	<fuji:loadhandler function="Fuji.init"/>
	<fuji:content>
		
		<!-- Page content -->
		<h1 class="logo"><a href="http://fuji.sourceforge.net">Fuji</a></h1>
		<div id="menuDiv" class="menuContainer box"/>
		<div class="sideContainer">
			<div id="foraDiv" class="forumListContainer box"/>
			<div id="calendarDiv" class="calendarContainer box"/>
		</div>
		<div id="threadsDiv"/>
		
		<!-- Dialogs -->
		<div id="createEditDialog" style="display: none">
			<div class="hd"><span id="createDialogHd"><?php echo $this->l10n->new ?></span><span id="editDialogHd"><?php echo $this->l10n->edit ?></span></div>
			<div class="bd">
				<form id="createEditForm" name="createEditForm" method="POST" action="item">
					<input id='createEditType' name='type' type='hidden' value='' />
					<input id='createEditProcessor' name='processor' type='hidden' value='' />
					<div><span><?php echo $this->l10n->title ?></span><input id="createEditTitle" name='title' type='text' value='' /></div>
					<div id='textEditorContainer'>
					<div class="editortoolbar">
						<img alt="Bold" src="style/default/graphics/button-bold.png" onclick="Fuji.addTags(document.getElementById('createCtent'), '[b][/b]')" />
						<img alt="Italic" src="style/default/graphics/button-italic.png" onclick="Fuji.addTags(document.getElementById('createCtent'), '[i][/i]')" />
						<img alt="Underline" src="style/default/graphics/button-underline.png" onclick="Fuji.addTags(document.getElementById('createCtent'), '[u][/u]')" />
						<img alt="Code" src="style/default/graphics/button-code.png" onclick="Fuji.addTags(document.getElementById('createCtent'), '[code][/code]')" />
						<img alt="Quote" src="style/default/graphics/button-quote.png" onclick="Fuji.addTags(document.getElementById('createCtent'), '[quote][/quote]')" />
						<img alt="URL" src="style/default/graphics/button-url.png" onclick="Fuji.addTags(document.getElementById('createCtent'), '[url][/url]')" />
						<img alt="Image" src="style/default/graphics/button-image.png" onclick="Fuji.addTags(document.getElementById('createCtent'), '[img][/img]')" />
					</div>
					<div><textarea id='createEditContent' name='content' type='text' value=''></textarea></div>
					</div>
					<div id="richTextEditorContainer" style="display:none">
						<div class="editortoolbar">
							<select onchange="Fuji.Editor.changeAction(this)">
								<option value="paragraph style"><?php echo $this->l10n->paragraph_style ?></option>
								<option value="p"><?php echo $this->l10n->paragraph ?></option>
								<option value="address"><?php echo $this->l10n->address ?></option>
								<option value="blockquote"><?php echo $this->l10n->quotation ?></option>
								<option value="h4"><?php echo $this->l10n->heading1 ?></option>
								<option value="h5"><?php echo $this->l10n->heading2 ?></option>
								<option value="h6"><?php echo $this->l10n->heading3 ?></option>
								<option value="pre"><?php echo $this->l10n->preformatted ?></option>
							</select>
							<select onchange="Fuji.Editor.changeAction(this)">
								<option value="character style"><?php echo $this->l10n->character_style ?></option>
								<option value="default"><?php echo $this->l10n->default ?></option>
								<option value="abbr"><?php echo $this->l10n->abbreviation ?></option>
								<option value="acronym"><?php echo $this->l10n->acronym ?></option>
								<option value="cite"><?php echo $this->l10n->citation ?></option>
								<option value="del"><?php echo $this->l10n->deleted ?></option>
								<option value="dfn"><?php echo $this->l10n->definition_term ?></option>
								<option value="ins"><?php echo $this->l10n->inserted ?></option>
								<option value="kbd"><?php echo $this->l10n->keyboard ?></option>
								<option value="em"><?php echo $this->l10n->emphasis ?></option>
								<option value="strong"><?php echo $this->l10n->strong_emphasis ?></option>
								<option value="code"><?php echo $this->l10n->computer_code ?></option>
								<option value="var"><?php echo $this->l10n->variable ?></option>
								<option value="samp"><?php echo $this->l10n->sample_output ?></option>
							</select>
							<div class="editorlink" onclick="Fuji.Editor.clickAction('createlink')"></div>
							<div class="editorimage" onclick="Fuji.Editor.clickAction('insertimage')"></div>
							<div class="editortable" onclick="Fuji.Editor.clickAction('createtable')"></div>
							<div class="editorol" onclick="Fuji.Editor.clickAction('insertol')"></div>
							<div class="editorul" onclick="Fuji.Editor.clickAction('insertul')"></div>
						</div>
						<iframe id="editor" onload="Fuji.Editor.init()"></iframe>
						<div><a href="javascript:Fuji.Editor.disable()"><?php echo $this->l10n->disable_rte ?></a></div>
					</div>
				</form>
			</div>
		</div>
		<div id="newGroupDialog" style="display: none">
			<div id="newGroupTitle" class="hd"><?php echo $this->l10n->new_group ?></div>
			<div id="newGroupForm" class="bd">
				<form id="newGroupF" name="newGroupF" class="createEditF" method="POST" action="groups/">
					<label><?php echo $this->l10n->name ?><input id="newGroupName" name='groupName' type="text" value='' /></label><br />
					<label><input type="checkbox" name="newGroupRead" id="newGroupRead" /><?php echo $this->l10n->read ?></label><br />
					<label><input type="checkbox" name="newGroupPost" id="newGroupPost" /><?php echo $this->l10n->post ?></label><br />
					<label><input type="checkbox" name="newGroupEdit" id="newGroupEdit" /><?php echo $this->l10n->edit ?></label><br />
					<label><input type="checkbox" name="newGroupDelete" id="newGroupDelete" /><?php echo $this->l10n->delete ?></label><br />
				</form>
			</div>
		</div>
		<div id="editGroupDialog" style="display: none">
			<div id="editGroupTitle" class="hd"><?php echo $this->l10n->edit_group ?></div>
			<div id="editGroupForm" class="bd">
				<form id="editGroupF" name="editGroupF" class="createEditF" method="PUT" action="groups/*">
					<label><input type="checkbox" name="editGroupRead" id="editGroupRead" /><?php echo $this->l10n->read ?></label><br />
					<label><input type="checkbox" name="editGroupPost" id="editGroupPost" /><?php echo $this->l10n->post ?></label><br />
					<label><input type="checkbox" name="editGroupEdit" id="editGroupEdit" /><?php echo $this->l10n->edit ?></label><br />
					<label><input type="checkbox" name="editGroupDelete" id="editGroupDelete" /><?php echo $this->l10n->delete ?></label><br />
				</form>
			</div>
		</div>
		<div id="configRightsDialog" style="display: none">
		</div>
		<?php if(UserManager::getUser()->isAdmin()) { ?>
		<div id="serverSettingsDialog" style="display: none">
			<div class="hd"><?php echo $this->l10n->preferences ?></div>
			<div class="bd">
				<form name="serverSettingsF" method="PUT" action="serversettings">
					<p><?php echo $this->l10n->default_language ?><br />
						<select name='default_language'>
							<?php
								$l10ns = L10nManager::getL10nData();
								$lang = L10nManager::getDefaultLanguage();
								foreach($l10ns as $l10n)
								{ 
									echo '<option value="' . $l10n['id'] . '"' . ($l10n['id'] == $lang ? ' selected="Selected"' : '') .  '>' . $l10n['name'] . '</option>';
								}
							?>
						</select>
					</p>
					<p><?php echo $this->l10n->default_style ?><br />
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
					<p><?php echo $this->l10n->gzip_compression ?><br />
						<select name='gzip'>
							<option value="0">0 (<?php echo $this->l10n->least_cpu_load ?>)</option>
							<option value="1">1</option>
							<option value="2">2</option>
							<option value="3">3</option>
							<option value="4">4</option>
							<option value="5">5</option>
							<option value="6" selected="Selected">6</option>
							<option value="7">7</option>
							<option value="8">8</option>
							<option value="9">9 (<?php echo $this->l10n->least_network_traffic ?>)</option>
						</select>
					</p>				
				</form>
			</div>
		</div>
		<?php } ?>			
		<div id="prefDialog" style="display: none">
			<div class="hd"><?php echo $this->l10n->preferences ?></div>
			<div class="bd">
				<form name="prefF" method="PUT" action="preferences">
					<fieldset>
						<legend><?php echo $this->l10n->password ?></legend>
						<p><?php echo $this->l10n->enter_your_new_password ?><br />
							<input id='prefPass1' name='pass1' type='password' value='' />
						</p>
						<p><?php echo $this->l10n->reenter_your_new_password ?><br />
							<input id='prefPass2' name='pass2' type='password' value='' />
						</p>
					</fieldset>
					<fieldset>
						<legend><?php echo $this->l10n->lang ?></legend>
						<select name='language'>
							<option value="NULL"><?php echo $this->l10n->default_language ?></option>
							<option disabled="disabled">━━━━━━━</option>
							<?php
								$l10ns = L10nManager::getL10nData();
								$lang = isset(UserManager::getUser()->language) ? UserManager::getUser()->language : '';
								foreach($l10ns as $l10n)
								{
									echo '<option value="' . $l10n['id'] . '"' . ($l10n['id'] == $lang ? ' selected="Selected"' : '') . '>' . $l10n['name'] . '</option>';
								}
							?>
						</select>
					</fieldset>
					<fieldset>
						<legend><?php echo $this->l10n->style ?></legend>
						<select name='style'>
							<option value="NULL"><?php echo $this->l10n->default_style ?></option>
							<option disabled="disabled">━━━━━━━</option>
							<?php
								$styles = Stylesheet::getStylesheets();
								$s = isset(UserManager::getUser()->style) ? UserManager::getUser()->style : '';
								foreach($styles as $style)
								{
									echo '<option value="' . $style->name . '"' . (($style->name == $s) ? ' selected="Selected"' : '') . '>' . $style->name . '</option>';
								}
							?>
						</select>
						<input name='visualEffects' type='checkbox' <?php if(UserManager::getUser()->visual_effects) echo 'checked="Checked"' ?> /><?php echo $this->l10n->visual_effects ?>
					</fieldset>				
					<fieldset>
						<legend><?php echo $this->l10n->profile ?></legend>
						<p><?php echo $this->l10n->email_address ?><br />
							<input id='prefEmail' name='email' type='text' value='<?php echo UserManager::getUser()->email ?>' />
							<input id='prefShowEmail' name='showEmail' type='checkbox' <?php if(UserManager::getUser()->email_visible) echo 'checked="Checked"' ?> /><?php echo $this->l10n->visible_in_profile ?>
						</p>
						<p><?php echo $this->l10n->profile_photo ?><br />
							<input id='prefPhoto' name='picture' type='text' value='<?php if(isset(UserManager::getUser()->picture)) echo UserManager::getUser()->picture ?>' />
						</p>
					</fieldset>
				</form>
			</div>
		</div>
	</fuji:content>
</fuji:item>