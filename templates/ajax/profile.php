<?php echo $this->getPrefix() ?>
<fuji:item xmlns="http://www.w3.org/1999/xhtml"
	xmlns:fuji="http://fuji.sourceforge.net/ns"
	xml:lang="<?php echo $this->l10n->language ?>"
	loadcontext="threadsDiv">
	<fuji:content>	
		<div class="box">
			<h2><?php echo $this->l10n->profile ?></h2>
			<div class="content">
				<table>
					<tr>
						<td rowspan="4"><?php if(isset($user->picture)) { ?><img width="60" src="<?php echo htmlentities($this->user->picture) ?>" alt="picture"></img><?php } ?></td>
						<td><?php echo $this->l10n->name ?>:</td><td><?php echo htmlentities($this->user->nickname, ENT_NOQUOTES) ?></td>
					</tr>
					<?php if (isset($this->user->email) && $this->user->email_visible) { ?>
					<tr>
						<td><?php echo $this->l10n->email ?>:</td><td><?php echo htmlentities($this->user->email, ENT_NOQUOTES); ?></td>
					</tr>
					<?php } ?>
					<tr>
						<td><?php echo $this->l10n->posts ?>:</td><td><?php echo $this->user->getPosts() ?></td>
					</tr>
					<tr>
						<td><?php echo $this->l10n->member_of ?>:</td>
						<td>
							<ul>
							<?php foreach ($this->groups as $group) { ?>
								<li><?php echo htmlentities($group->name, ENT_NOQUOTES) ?></li>
							<?php } ?>
							</ul>
						</td>
					</tr>
				</table>
			</div>
		</div>
	</fuji:content>
</fuji:item>