<?php echo $this->getPrefix() ?>
<fuji:item xmlns="http://www.w3.org/1999/xhtml"
	xmlns:fuji="http://fuji.sourceforge.net/ns"
	xml:lang="<?php echo $this->l10n->language ?>"
	loadcontext="threadsDiv">
	<fuji:content>
		<div class="box">
			<h2><?php echo $this->l10n->users; ?></h2>
			<ul class="menu">
			<?php if(UserManager::isAdmin()) { ?>
				<li><a href="javascript:Fuji.Dialogs.newCreateUserDialog()"><?php echo $this->l10n->new ?></a></li>
			<?php } ?>
			</ul>
			<div class="content">
				<table>
					<thead>
						<tr>
							<th><?php echo $this->l10n->name ?></th>
							<th><?php echo $this->l10n->email_address ?></th>
							<th><?php echo $this->l10n->group ?></th>
							<th><?php echo $this->l10n->posts ?></th>
							<th><?php echo $this->l10n->actions ?></th>
						</tr>
					</thead>
					<tbody>
					<?php foreach ($this->users as $user) { ?>
						<tr class="tableUser">
							<td><a href="#<?php echo $user->getURI() ?>"><?php echo htmlentities($user->nickname, ENT_NOQUOTES) ?></a></td>
							<td><?php if ($user->email_visible) { ?><a href="mailto:<?php echo htmlentities($user->email, ENT_NOQUOTES) ?>"><?php echo htmlentities($user->email, ENT_NOQUOTES) ?></a><?php } ?></td>
							<td><?php
								$groups = $user->getGroups();
								$groupstring = '';
								foreach ($groups as $group)
									$groupstring .= htmlentities($group->name, ENT_NOQUOTES) . ',';
								echo rtrim($groupstring, ',');
							?></td>
							<td><?php echo $user->getPosts() ?></td>
							<td>
							<?php if (UserManager::isAdmin()) { ?>
								<span><a href="javascript:Fuji.Dialogs.newEditUserDialog('users/<?php echo $user->id ?>')"><?php echo $this->l10n->edit ?></a></span>
								<span><a href="javascript:Fuji.Dialogs.newDeleteDialog('users/<?php echo $user->id ?>','users')"><?php echo $this->l10n->delete ?></a></span>
							<?php } ?>
							</td>
						</tr>
					<?php } ?>
					<?php if (count($this->users) == 0) { ?>
						<tr class="tableUser">
							<td colspan="5"><?php echo $this->l10n->error_no_users ?></td>
						</tr>
					<?php } ?>
					</tbody>
				</table>
			</div>
			<div id="userDialog" style="display: none">
				<div id="userDialogHead" class="hd"><?php echo $this->l10n->user ?></div>
				<div id="userDialogBody" class="bd">
					<form id="userDialogForm" name="userDialogForm" class="userDialogForm" method="POST" action="users">
						<fieldset>
							<legend><?php echo $this->l10n->account ?></legend>
							<p><?php echo $this->l10n->name ?><br />
								<input id='userName' name='nickname' type='text' value='' />
							</p>
							<p><?php echo $this->l10n->enter_your_new_password ?><br />
								<input id='userPass' name='password' type='text' value='' />
							</p>
						</fieldset>
						<fieldset>
							<legend><?php echo $this->l10n->profile ?></legend>
							<p><?php echo $this->l10n->email_address ?><br />
								<input id='userEmail' name='email' type='text' value='' />
							</p>
							<p><?php echo $this->l10n->profile_photo ?><br />
								<input id='userPhoto' name='picture' type='text' value='<?php if(isset(UserManager::getUser()->picture)) echo UserManager::getUser()->picture ?>' />
							</p>
						</fieldset>
					</form>
				</div>
			</div>
		</div>
	</fuji:content>
</fuji:item>