<?php
	$page_size = 8;
	$userstatus = (UserManager::getUser()->nickname == 'anonymous' ? Plugin::$USER_ANONYMOUS : (UserManager::getUser()->isAdmin() ? Plugin::$USER_ADMINISTRATOR : Plugin::$USER_LOGGED_IN));
?>
<?php echo $this->getPrefix() ?>
<fuji:item xmlns="http://www.w3.org/1999/xhtml"
	xmlns:fuji="http://fuji.sourceforge.net/ns"
	xml:lang="<?php echo $this->l10n->language ?>"
	loadcontext="threadsDiv"
	parent="item/1">
	<fuji:content>
		<div class="forumContainer box withpager">
		<h2><?php echo $this->l10n->threads ?> – <?php echo htmlentities($this->entry->title, ENT_NOQUOTES) ?></h2>
		<ul class="menu">
			<?php if ($this->entry->getRights()->may_post) { ?>
				<li><a href="javascript:Fuji.Dialogs.newCreateDialog('<?php echo $this->entry->getURI() ?>', <?php echo $this->entry->type + 1 ?>)"><?php echo $this->l10n->new ?></a></li>
			<?php } if ($this->entry->getRights()->may_edit) { ?>
				<li><a href="javascript:Fuji.Dialogs.newEditDialog('<?php echo $this->entry->getURI() ?>')"><?php echo $this->l10n->edit ?></a></li>
			<?php } if ($this->entry->getRights()->may_delete) { ?>
				<li><a href="javascript:Fuji.Dialogs.newDeleteDialog('<?php echo $this->entry->getURI() ?>', '<?php echo $this->entry->getParent()->getURI() ?>')"><?php echo $this->l10n->delete ?></a></li>
			<?php } if (UserManager::isAdmin()) { ?>
				<li><a href="javascript:Fuji.Dialogs.newRightsDialog('<?php echo $this->entry->getURI() ?>')"><?php echo $this->l10n->config_rights ?></a></li>
			<?php } ?>
			<li><a href="<?php echo $this->entry->getURI() ?>.rss" rel="alternate" type="application/rss+xml" class="feed">RSS</a></li>
			<li><a href="<?php echo $this->entry->getURI() ?>.atom" rel="alternate" type="application/atom+xml" class="feed">Atom</a></li>
			<li><a href="javascript://" class="submenu">Semantics</a>
				<ul>
					<li><a href="javascript:alert('not implemented');">Add calendar event</a></li>
					<li><a href="javascript:alert('not implemented');">Add tag</a></li>
					<li><a href="javascript:alert('not implemented');">Add tag</a></li>
				</ul>
			</li>
			<?php $this->plugin->getMenu(Plugin::$TEMPLATE_AJAX, $userstatus, Plugin::$MENU_FORUMCONTAINER) ?>
		</ul>
		<div class="content">
			<div may_post="<?php echo $this->entry->getRights()->may_post ?>" may_edit="<?php echo $this->entry->getRights()->may_edit ?>" may_delete="<?php echo $this->entry->getRights()->may_delete ?>">
			<div class="forumcontent"><?php echo $this->entry->getProcessedContent() ?></div>
			<table>
				<colgroup>
					<col width="40%" />
					<col />
					<col />
					<col />
					<col />
				</colgroup>
				<thead>
					<tr>
						<th><?php echo $this->l10n->topic ?></th>
						<th><?php echo $this->l10n->poster ?></th>
						<th><?php echo $this->l10n->replies ?></th>
						<th><?php echo $this->l10n->created ?></th>
						<th><?php echo $this->l10n->modified ?></th>
					</tr>
				</thead>
				<tbody>
				<?php 	$num_of_children = count($this->entry->getChildren());
						$i = 0;
						if($num_of_children > 0)
						foreach($this->entry->getChildren('type, created DESC', ($this->page - 1) * $page_size . ', ' . $page_size) as $post) { ?>
						<?php if ($post->type == Entry::$TYPE_FORUM) { ?>
						<tr class="tableForumEntry <?php echo $i++ % 2 ? 'even' : 'odd' ?>">
							<td><a href="#<?php echo $post->getURI() ?>"><?php echo htmlentities($post->title, ENT_NOQUOTES) ?></a></td>
							<td><a href="#<?php echo User::getUser($post->userref)->getURI() ?>"><?php echo htmlentities(User::getUser($post->userref)->nickname, ENT_NOQUOTES) ?></a></td>
							<td><?php echo count($post->getChildren()) ?></td>
							<td><?php echo $post->getFormattedDate('created', $this->l10n->datetime_format) ?></td>
							<td><?php echo $post->getFormattedDate('modified', $this->l10n->datetime_format) ?></td>
						
						<?php } else if ($post->type == Entry::$TYPE_POST) { ?>
						<tr class="tableThreadEntry <?php echo $i++ % 2 ? 'even' : 'odd' ?>">				
							<td><a href="#<?php echo $post->getURI() ?>"><?php echo htmlentities($post->title, ENT_NOQUOTES) ?></a></td>
							<td><a href="#<?php echo User::getUser($post->userref)->getURI() ?>"><?php echo htmlentities(User::getUser($post->userref)->nickname, ENT_NOQUOTES) ?></a></td>
							<td><?php echo count($post->getChildren()) ?></td>
							<td><?php echo $post->getFormattedDate('created', $this->l10n->datetime_format) ?></td>
							<td><?php echo $post->getFormattedDate('modified', $this->l10n->datetime_format) ?></td>
						<?php } else { ?>
						<tr class="tableUnsupportedEntry">				
							(<?php echo $this->l10n->type_unsupported ?>)
						
						<?php } ?>
					</tr>
				<?php } else echo $this->l10n->error_no_threads
					// TODO: Invalid XHTML here!
					?>
				</tbody>
			</table>
			</div>
		</div>
		<div class="pager">
			<?php echo $this->l10n->page?>
			<?php if($this->page > 1) { ?><a href="#<?php echo $this->entry->getURI().'/'.($this->page-1) ?>">&lt;</a> <?php } ?>
			<?php for($n = 1; $n <= max(0, $num_of_children - 1) / $page_size + 1; $n++) { ?><a href="#<?php echo $this->entry->getURI().($n>1?'/'.$n:'') ?>"><?php echo $n ?></a> <?php } ?>
			<?php if(max(0, $num_of_children - 1) / $page_size > $this->page) { ?><a href="#<?php echo $this->entry->getURI().'/'.($this->page+1) ?>">&gt;</a> <?php } ?>
		</div>
		</div>
		<div id="<?php echo $this->entry->getURI() ?>_postsDiv"></div>
	</fuji:content>
</fuji:item>
