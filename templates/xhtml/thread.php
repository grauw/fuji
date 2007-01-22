<?php echo $this->getPrefix();
	$photo_placeholder = '../style/default/graphics/no-photo.png'; ?>
<!DOCTYPE html
	PUBLIC "-//W3C//DTD XHTML 1.1 plus MathML 2.0//EN"
	"http://www.w3.org/Math/DTD/mathml2/xhtml-math11-f.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->l10n->language ?>">
<head>
	<title><?php echo $this->entry->title ?></title>
	<meta http-equiv="Content-Type" content="<?php echo $this->acceptsXHTML() ? 'application/xhtml+xml' : 'text/html' ?>; charset=utf-8" />
	<link rel="alternate" type="application/atom+xml" title="Atom" href="/blog/atom.php" />
	<link rel="alternate" type="application/rss+xml" title="RSS" href="/blog/rss.php" />
	<link rel="meta" type="application/rdf+xml" title="FOAF" href="foaf.rdf" />
	<link rel="icon" type="image/png" href="/images/siteicon.png" />
	<?php echo $this->getHead(1) ?>
</head>
<body>
	<h1 class="logo"><a href="http://fuji.sourceforge.net">Fuji</a></h1>
	<div id="menuDiv" class="menuContainer box">
	<h2><?php echo $this->l10n->menu ?></h2>
	<?php if(UserManager::getUser()->nickname == 'anonymous') { ?>
		<p><?php echo $this->l10n->anonymous_user ?></p>
	<?php } else { ?>
		<p><?php echo $this->l10n->logged_in_as . ' ' . UserManager::getUser()->nickname ?></p>
	<?php } ?>
	<ul>
		<li><a href="<?php echo $this->entry->getParent()->getRootedURI() ?>"><?php echo $this->l10n->back ?></a></li>
	</ul>
	</div>	
	<div class="box">
	<h2><?php echo $this->l10n->posts ?> – <?php echo htmlentities($this->entry->title, ENT_NOQUOTES) ?></h2>
	
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
					<span><a href="<?php echo User::getUser($this->entry->userref)->getRootedURI() ?>"><?php echo htmlentities(User::getUser($this->entry->userref)->nickname, ENT_NOQUOTES) ?></a></span>
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
						<span><a href="<?php echo User::getUser($post->userref)->getRootedURI() ?>"><?php echo htmlentities(User::getUser($post->userref)->nickname, ENT_NOQUOTES) ?></a></span>
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
	</div>
</body>
</html>