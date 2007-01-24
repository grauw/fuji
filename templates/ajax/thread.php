<?php
	$page_size = 20; 
	$photo_placeholder = 'style/default/graphics/no-photo.png';
	$userstatus = (UserManager::getUser()->nickname == 'anonymous' ? Plugin::$USER_ANONYMOUS : (UserManager::getUser()->isAdmin() ? Plugin::$USER_ADMINISTRATOR : Plugin::$USER_LOGGED_IN));
?>
<?php echo $this->getPrefix() ?>
<?php
$contextEntry = $this->entry;
while($contextEntry->type == 4) $contextEntry = $contextEntry->getParent();
?>
<fuji:item xmlns="http://www.w3.org/1999/xhtml"
	xmlns:fuji="http://fuji.sourceforge.net/ns"
	xml:lang="<?php echo $this->l10n->language ?>"
	loadcontext="<?php echo $contextEntry->getURI() ?>_postsDiv"
	parent="<?php echo $this->entry->getParent()->getURI() ?>">
	<fuji:content>
		<div class="threadContainer box withpager">
		<h2><?php echo $this->l10n->posts ?> – <?php echo htmlentities($this->entry->title, ENT_NOQUOTES) ?></h2>
		<ul class="menu">
			<?php if (UserManager::getUser()->getRights($this->entry)->may_post) { ?>
				<li><a href="javascript:Fuji.Dialogs.newCreateDialog('<?php echo $this->entry->getURI() ?>', '4')"><?php echo $this->l10n->reply ?></a></li>
			<?php } if (UserManager::getUser()->getRights($this->entry)->may_delete) { ?>
				<li><a href="javascript:Fuji.Dialogs.newDeleteDialog('<?php echo $this->entry->getURI() ?>', '<?php echo $this->entry->getParent()->getURI() ?>')"><?php echo $this->l10n->delete ?></a></li>
			<?php } ?>
			<li><a href="<?php echo $this->entry->getURI() ?>.rss" rel="alternate" type="application/rss+xml" class="feed">RSS</a></li>
			<li><a href="<?php echo $this->entry->getURI() ?>.atom" rel="alternate" type="application/atom+xml" class="feed">Atom</a></li>
			<?php $this->plugin->getMenu(Plugin::$TEMPLATE_AJAX, $userstatus, Plugin::$MENU_THREADCONTAINER) ?>
		</ul>
		<div class="postsContainer content">
			<div class="box odd">
				<div class="userinfo">
					<img src="<?php echo (isset(User::getUser($this->entry->userref)->picture) && User::getUser($this->entry->userref)->picture ? htmlentities(User::getUser($this->entry->userref)->picture) : $photo_placeholder) ?>" alt="profilephoto" class="profilePhoto" />
				</div>
				<h3>
					<div>
					<span><?php echo $this->entry->title ?></span>
					<span><?php echo $this->entry->getFormattedDate('modified', $this->l10n->datetime_format) ?></span>
					</div>
					<div>
					<span><a href="#<?php echo User::getUser($this->entry->userref)->getURI() ?>"><?php echo htmlentities(User::getUser($this->entry->userref)->nickname, ENT_NOQUOTES) ?></a></span>
					<span>(<?php $output = ""; $groups = User::getUser($this->entry->userref)->getGroups(); foreach($groups as $group) { $output .= ($output ? ', ' : '') . htmlentities($group->name, ENT_NOQUOTES); } echo $output ?>)</span>
					</div>
				</h3>
				<?php if (UserManager::getUser()->getRights($this->entry)->may_edit) { ?>
				<ul class="menu">
					<?php if (UserManager::getUser()->getRights($this->entry)->may_edit) { ?>
						<li><a href="javascript:Fuji.Dialogs.newEditDialog('<?php echo $this->entry->getURI() ?>')"><?php echo $this->l10n->edit ?></a></li>
					<?php } ?>
				</ul>
				<?php } ?>
				<div class="content"><?php echo $this->entry->getProcessedContent() ?></div>
			</div>
			<?php 	$num_of_children = count($this->entry->getChildren());
					$i = 1;
					if($num_of_children > 0)
					foreach($this->entry->getChildren('created', ($this->page - 1) * $page_size . ', ' . $page_size) as $post) { ?>
			<div class="box <?php echo $i++ % 2 ? 'even' : 'odd' ?>">
				<?php if ($post->type == Entry::$TYPE_POST) { ?>
					<div class="userinfo">
						<img src="<?php echo (isset(User::getUser($post->userref)->picture) && User::getUser($post->userref)->picture ? htmlentities(User::getUser($post->userref)->picture) : $photo_placeholder) ?>" alt="profilephoto" />
					</div>
					<h3>
						<div>
						<span><?php echo $post->title ?></span>
						<span><?php echo $post->getFormattedDate('modified', $this->l10n->datetime_format) ?></span>
						</div>
						<div>
						<span><a href="#<?php echo User::getUser($post->userref)->getURI() ?>"><?php echo htmlentities(User::getUser($post->userref)->nickname, ENT_NOQUOTES) ?></a></span>
						<span>(<?php $output = ""; $groups = User::getUser($post->userref)->getGroups(); foreach($groups as $group) { $output .= ($output ? ', ' : '') . htmlentities($group->name, ENT_NOQUOTES); } echo $output ?>)</span>
						</div>
					</h3>
					<?php if (UserManager::getUser()->getRights($post)->may_edit || UserManager::getUser()->getRights($post)->may_delete) { ?>
					<ul class="menu">
						<?php if (UserManager::getUser()->getRights($post)->may_edit) { ?>
							<li><a href="javascript:Fuji.Dialogs.newEditDialog('<?php echo $post->getURI() ?>')"><?php echo $this->l10n->edit ?></a></li>
						<?php } if (UserManager::getUser()->getRights($post)->may_delete) { ?>
							<li><a href="javascript:Fuji.Dialogs.newDeleteDialog('<?php echo $post->getURI() ?>', '<?php echo $post->getParent()->getURI() ?>')"><?php echo $this->l10n->delete ?></a></li>
						<?php } ?>
					</ul>
					<?php } ?>
					<div class="content"><?php echo $post->getProcessedContent() ?></div>
				
				<?php } else { ?>
				
					(<?php echo $this->l10n->type_unsupported ?>)
				
				<?php } ?>
			</div>
			<?php } ?>
		</div>
		<div class="pager">
			<?php echo $this->l10n->page?>
			<?php if($this->page > 1) { ?><a href="#<?php echo $this->entry->getURI().'/'.($this->page-1) ?>">&lt;</a> <?php } ?>
			<?php for($n = 1; $n <= max(0, $num_of_children - 1) / $page_size + 1; $n++) { ?><a href="#<?php echo $this->entry->getURI().($n>1?'/'.$n:'') ?>"><?php echo $n ?></a> <?php } ?>
			<?php if(max(0, $num_of_children - 1) / $page_size > $this->page) { ?><a href="#<?php echo $this->entry->getURI().'/'.($this->page+1) ?>">&gt;</a> <?php } ?>
		</div>
		</div>
	</fuji:content>
</fuji:item>