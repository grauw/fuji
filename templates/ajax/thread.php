<?php $this->page_size = 20; ?>
<?xml version="1.0" encoding="UTF-8"?>
<?php
$contextEntry = $this->entry;
while($contextEntry->type == 4) $contextEntry = $contextEntry->getParent();
?>
<fuji:item xmlns="http://www.w3.org/1999/xhtml"
	xmlns:fuji="http://fuji.sourceforge.net/ns"
	xml:lang="<?php echo L10n::$language ?>"
	loadcontext="<?php echo $contextEntry->getURI() ?>_postsDiv"
	parent="<?php echo $this->entry->getParent()->getURI() ?>">
	<fuji:content>
		<div class="threadContainer box withpager">
		<h2 class="windowTitle"><?php echo L10n::$posts ?> – <?php echo htmlentities($this->entry->title, ENT_NOQUOTES) ?></h2>
		<ul id="postsActions" class="windowActions menu">
			<?php if (UserManager::getUser()->getRights($this->entry)->may_post) { ?>
				<li id="actionReplyPost"><a href="javascript:Fuji.Dialogs.newCreateDialog('<?php echo $this->entry->getURI() ?>', '4')"><?php echo L10n::$reply ?></a></li>
			<?php } if (UserManager::getUser()->getRights($this->entry)->may_delete) { ?>
				<li id="actionDeletePost"><a href="javascript:Fuji.Dialogs.newDeleteDialog('<?php echo $this->entry->getURI() ?>', '<?php echo $this->entry->getParentURI() ?>')"><?php echo L10n::$delete ?></a></li>
			<?php } ?>
			<li><a href="rss/<?php echo $this->entry->getURI() ?>" rel="alternate" type="application/rss+xml" class="feed">RSS</a></li>
			<li><a href="atom/<?php echo $this->entry->getURI() ?>" rel="alternate" type="application/atom+xml" class="feed">Atom</a></li>
		</ul>
		<div class="postsContainer content">
			<div class="postDiv box odd">
				<h3 class="postTitle">
					<span><?php echo $this->entry->title ?></span>
					<span>(<?php echo L10n::$posted_by ?> <a href="#<?php echo User::getUser($this->entry->userref)->getURI() ?>"><?php echo htmlentities(User::getUser($this->entry->userref)->nickname, ENT_NOQUOTES) ?></a>
						<?php echo L10n::$on ?> <?php echo $this->entry->getFormattedDate('modified', L10n::$date_format) ?>)</span>
				</h3>
				<?php if (UserManager::getUser()->getRights($this->entry)->may_edit) { ?>
				<ul class="windowActions menu">
					<?php if (UserManager::getUser()->getRights($this->entry)->may_edit) { ?>
						<li id="actionEditPost"><a href="javascript:Fuji.Dialogs.newEditDialog('<?php echo $this->entry->getURI() ?>')"><?php echo L10n::$edit ?></a></li>
					<?php } ?>
				</ul>
				<?php } ?>
				<div class="postContent content"><?php echo $this->entry->getProcessedContent() ?></div>
			</div>
			<?php 	$num_of_children = count($this->entry->getChildren());
					$i = 1;
					if($num_of_children > 0)
					foreach($this->entry->getChildren('created', ($this->page - 1) * $this->page_size . ', ' . $this->page_size) as $post) { ?>
			<div class="postDiv box <?php echo $i++ % 2 ? 'even' : 'odd' ?>">
				<?php if ($post->type == Entry::$TYPE_POST) { ?>
					<h3 class="postTitle">
						<span><?php echo htmlentities($post->title, ENT_NOQUOTES) ?></span>
						<span>(<?php echo L10n::$posted_by ?> <a href="#<?php echo User::getUser($post->userref)->getURI() ?>"><?php echo htmlentities(User::getUser($post->userref)->nickname, ENT_NOQUOTES) ?></a>
							<?php echo L10n::$on ?> <?php echo $post->getFormattedDate('modified', L10n::$date_format) ?>)</span>
					</h3>
					<?php if (UserManager::getUser()->getRights($post)->may_edit || UserManager::getUser()->getRights($post)->may_delete) { ?>
					<ul class="windowActions menu">
						<?php if (UserManager::getUser()->getRights($post)->may_edit) { ?>
							<li><a href="javascript:Fuji.Dialogs.newEditDialog('<?php echo $post->getURI() ?>')"><?php echo L10n::$edit ?></a></li>
						<?php } if (UserManager::getUser()->getRights($post)->may_delete) { ?>
							<li><a href="javascript:Fuji.Dialogs.newDeleteDialog('<?php echo $post->getURI() ?>', '<?php echo $post->getParentURI() ?>')"><?php echo L10n::$delete ?></a></li>
						<?php } ?>
					</ul>
					<?php } ?>
					<div class="postContent content"><?php echo $post->getProcessedContent() ?></div>
				
				<?php } else { ?>
				
					(<?php echo L10n::$type_unsupported ?>)
				
				<?php } ?>
			</div>
			<?php } ?>
		</div>
		<div class="threadPageSelector pager">
			<?php echo L10n::$page?>
			<?php if($this->page > 1) { ?><a href="#<?php echo $this->entry->getURI().'/'.($this->page-1) ?>">&lt;</a> <?php } ?>
			<?php for($n = 1; $n <= max(0, $num_of_children - 1) / $this->page_size + 1; $n++) { ?><a href="#<?php echo $this->entry->getURI().($n>1?'/'.$n:'') ?>"><?php echo $n ?></a> <?php } ?>
			<?php if(max(0, $num_of_children - 1) / $this->page_size > $this->page) { ?><a href="#<?php echo $this->entry->getURI().'/'.($this->page+1) ?>">&gt;</a> <?php } ?>
		</div>
		</div>
	</fuji:content>
</fuji:item>