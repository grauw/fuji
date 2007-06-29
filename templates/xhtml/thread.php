<?php
	$page_size = 20; 
	$photo_placeholder = '../style/default/graphics/no-photo.png';
	echo $this->getPrefix();
?>
<!DOCTYPE html
	PUBLIC "-//W3C//DTD XHTML 1.1 plus MathML 2.0//EN"
	"http://www.w3.org/Math/DTD/mathml2/xhtml-math11-f.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->l10n->language ?>">
<head>
	<title><?php echo htmlentities($this->entry->title, ENT_NOQUOTES) ?></title>
	<?php echo $this->getHead() ?>
</head>
<body>
	<h1 class="logo"><a href="<?php echo $this->l10n->forum_link ?>"><?php echo $this->l10n->forum_name ?></a></h1>
	<div id="menuDiv" class="menuContainer box">
	<!--<h2><?php echo $this->l10n->menu ?></h2>-->
	<p>
		<?php if(UserManager::getUser()->nickname == 'anonymous') { ?>
			<?php echo $this->l10n->anonymous_user ?>
		<?php } else { ?>
			<?php echo $this->l10n->logged_in_as . ' ' . UserManager::getUser()->nickname ?>
		<?php } ?>
		<?php if(UserManager::getUser()->nickname == 'anonymous') { ?>
			(<a href="<?php echo $this->getForumRoot() ?>session-login"><?php echo $this->l10n->login ?></a>)
		<?php } else { ?>
			(<a href="<?php echo $this->getForumRoot() ?>session-logout"><?php echo $this->l10n->logout ?></a>)
		<?php } ?>
	</p>
	<p><?php echo $this->l10n->location ?>: <?php echo $this->getBreadcrumb() ?></p>
	</div>
	<div class="box">
	<h2><?php echo htmlentities($this->entry->title, ENT_NOQUOTES) ?></h2>
	
	<div class="postsContainer content">
	
			<div class="box odd">
				<h3><?php echo htmlentities($this->entry->title, ENT_NOQUOTES) ?></h3>
<!--				<div class="userinfo">
					<img src="<?php echo (isset(User::getUser($this->entry->userref)->picture) && User::getUser($this->entry->userref)->picture ? htmlentities(User::getUser($this->entry->userref)->picture) : $photo_placeholder) ?>" alt="profilephoto" class="profilePhoto" />
				</div>
-->				<p>
					<?php
						printf($this->l10n->posted_by_at,
							'<a href="' . User::getUser($this->entry->userref)->getRootedURI() . '">' . htmlentities(User::getUser($this->entry->userref)->nickname, ENT_NOQUOTES) . '</a>', 
							$this->entry->getFormattedDate('created', $this->l10n->datetime_format));
					?>
				</p>
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
<!--					<div class="userinfo">
						<img src="<?php echo (isset(User::getUser($post->userref)->picture) && User::getUser($post->userref)->picture ? htmlentities(User::getUser($post->userref)->picture) : $photo_placeholder) ?>" alt="profilephoto" />
					</div>
-->					<h3><?php echo $post->title ?></h3>
					<p>
						<?php
							printf($this->l10n->posted_by_at,
								'<a href="' . User::getUser($post->userref)->getRootedURI() . '">' . htmlentities(User::getUser($post->userref)->nickname, ENT_NOQUOTES) . '</a>', 
								$post->getFormattedDate('created', $this->l10n->datetime_format));
						?>
					</p>
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
			
			<?php if (UserManager::getUser()->getRights($this->entry)->may_post) { ?>
			<div class="box <?php echo $i++ % 2 ? 'even' : 'odd' ?> postreply">
				<h3><?php echo $this->l10n->reply ?></h3>
				<form action="" method="POST" class="content">
					<input type="hidden" name="type" value="4" />
					<input type="hidden" name="processor" value="xhtml" />
					<label><?php echo $this->l10n->title ?>:
						<input name="title" style="width:30em;" />
					</label><br />
					<label><?php echo $this->l10n->message ?>:<br />
						<textarea name="content" style="width:40em;"></textarea>
					</label><br />
					<button><?php echo $this->l10n->submit ?></button>
				</form>
			</div>
			<?php } ?>
		</div>
	</div>
</body>
</html>