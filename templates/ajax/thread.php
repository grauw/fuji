<?php $page_size = 20; ?>
<?php echo $template->getPrefix(); ?>
<?php
$parentEntry = $entry;
while($parentEntry->type == 4) $parentEntry = $parentEntry->getParent();
$contextId = $parentEntry->id;
?>
<fuji:item xmlns="http://www.w3.org/1999/xhtml"
	xmlns:fuji="http://fuji.sourceforge.net/ns"
	xml:lang="<?php echo L10n::$language ?>"
	loadcontext="item/<?php echo $contextId?>_postsDiv"
	parent="item/<?php echo $entry->getParent()->id ?>/">
	<fuji:content>
		<div class="threadContainer box">
		<h2 class="windowTitle"><?php echo L10n::$posts ?> – <?php echo htmlentities($entry->title, ENT_NOQUOTES) ?></h2>
		<ul id="postsActions" class="windowActions menu">
			<?php if (UserManager::getUser()->getRights($entry)->may_post) { ?>
				<li id="actionReplyPost"><a href="javascript:Fuji.newCreateDialog('<?php echo $entry->id ?>', '4')"><?php echo L10n::$reply ?></a></li>
			<?php } if (UserManager::getUser()->getRights($entry)->may_delete) { ?>
				<li id="actionDeletePost"><a href="javascript:Fuji.newDeleteDialog('<?php echo $entry->id ?>', '<?php echo $entry->ref ?>')"><?php echo L10n::$delete ?></a></li>
			<?php } ?>
			<li><a href="rss/item/<?php echo $entry->id ?>" rel="alternate" type="application/rss+xml" class="feed">RSS</a></li>
			<li><a href="atom/item/<?php echo $entry->id ?>" rel="alternate" type="application/atom+xml" class="feed">Atom</a></li>
		</ul>
		<div class="postsListDiv content">
			<div class="postDiv post odd">
				<h3 class="postTitle">
					<span><?php echo $entry->title ?></span>
					<span>(<?php echo L10n::$posted_by ?> <a href="#profile/<?php echo User::getUser($entry->userref)->id ?>"><?php echo htmlentities(User::getUser($entry->userref)->nickname, ENT_NOQUOTES) ?></a>
						<?php echo L10n::$on ?> <?php echo $entry->getFormattedDate('modified', L10n::$date_format) ?>)</span>
				</h3>
				<?php if (UserManager::getUser()->getRights($entry)->may_edit) { ?>
				<ul class="windowActions menu">
					<?php if (UserManager::getUser()->getRights($entry)->may_edit) { ?>
						<li id="actionEditPost"><a href="javascript:Fuji.newEditDialog('<?php echo $entry->id ?>')"><?php echo L10n::$edit ?></a></li>
					<?php } ?>
				</ul>
				<?php } ?>
				<div class="postContent content"><?php echo $entry->getProcessedContent() ?></div>
			</div>
			<?php 	$num_of_children = count($entry->getChildren());
					$i = 1;
					if($num_of_children > 0)
					foreach($entry->getChildren('created', ($page - 1) * $page_size . ', ' . $page_size) as $post) { ?>
			<div class="postDiv post <?php echo $i++ % 2 ? 'even' : 'odd' ?>">
				<?php if ($post->type == Entry::$TYPE_POST) { ?>
					<h3 class="postTitle">
						<span><?php echo htmlentities($post->title, ENT_NOQUOTES) ?></span>
						<span>(<?php echo L10n::$posted_by ?> <a href="#profile/<?php echo User::getUser($post->userref)->id ?>"><?php echo htmlentities(User::getUser($post->userref)->nickname, ENT_NOQUOTES) ?></a>
							<?php echo L10n::$on ?> <?php echo $post->getFormattedDate('modified', L10n::$date_format) ?>)</span>
					</h3>
					<?php if (UserManager::getUser()->getRights($post)->may_edit || UserManager::getUser()->getRights($post)->may_delete) { ?>
					<ul class="windowActions menu">
						<?php if (UserManager::getUser()->getRights($post)->may_edit) { ?>
							<li><a href="javascript:Fuji.newEditDialog('<?php echo $post->id ?>')"><?php echo L10n::$edit ?></a></li>
						<?php } if (UserManager::getUser()->getRights($post)->may_delete) { ?>
							<li><a href="javascript:Fuji.newDeleteDialog('<?php echo $post->id ?>', '<?php echo $post->ref ?>')"><?php echo L10n::$delete ?></a></li>
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
			<?php if($page > 1) { ?><a href="#<?php echo 'item/'.$entry->id.'/'.($page-1) ?>">&lt;</a> <?php } ?>
			<?php for($n = 1; $n <= max(0, $num_of_children - 1) / $page_size + 1; $n++) { ?><a href="#<?php echo 'item/'.$entry->id.'/'.($n>1?$n:'') ?>"><?php echo $n ?></a> <?php } ?>
			<?php if(max(0, $num_of_children - 1) / $page_size > $page) { ?><a href="#<?php echo 'item/'.$entry->id.'/'.($page+1) ?>">&gt;</a> <?php } ?>
		</div>
		</div>
	</fuji:content>
</fuji:item>