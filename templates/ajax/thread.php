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
		<h2 class="windowTitle"><?php echo L10n::$posts ?> – <?php echo $entry->title ?></h2>
		<ul id="postsActions" class="windowActions menu">
			<?php
				if (UserManager::getUser()->getRights($entry)->may_post) {
					?><li id="actionReplyPost"><a href="javascript:Fuji.newCreateDialog('<?php echo $entry->id ?>', '4')"><?php echo L10n::$reply ?></a></li><?php
				}
				if (UserManager::getUser()->getRights($entry)->may_edit) {
					?><li id="actionEditPost"><a href="javascript:Fuji.newEditDialog('<?php echo $entry->id ?>')"><?php echo L10n::$edit ?></a></li><?php
				}
				if (UserManager::getUser()->getRights($entry)->may_delete) {
					?><li id="actionDeletePost"><a href="javascript:Fuji.newDeleteDialog('<?php echo $entry->id ?>', '<?php echo $entry->ref ?>')"><?php echo L10n::$delete ?></a></li><?php
				}
				/*if (UserManager::isAdmin()) { 
					?><li id="actionConfigRights"><a href="javascript:Fuji.newConfigRightsDialog('<?php echo $entry->id ?>')"><?php echo L10n::$config_rights ?></a></li><?php
				}*/						
			?><li><a href="rss/<?php echo $entry->id ?>" rel="alternate" type="application/rss+xml" class="feed">RSS</a></li><?php
			?><li><a href="atom/<?php echo $entry->id ?>" rel="alternate" type="application/atom+xml" class="feed">Atom</a></li>
		</ul>
		<div class="postsListDiv content">
			<div class="postDiv odd">
					<h3 class="postTitle">
						<span><?php echo $entry->title ?></span>
						<span>(<?php echo L10n::$posted_by ?> <a href="profile/<?php echo User::getUser($entry->userref)->id ?>" target="_blank"><?php echo User::getUser($entry->userref)->nickname ?></a>
							<?php echo L10n::$on ?> <?php echo $entry->getFormattedDate('modified', L10n::$date_format) ?>)</span>
					</h3>
					<div class="postContent"><?php echo $entry->getProcessedContent() ?></div>
			</div>
			<?php 	$num_of_children = count($entry->getChildren());
					$i = 1;
					if($num_of_children > 0)
					foreach($entry->getChildren('created', ($page - 1) * $page_size . ', ' . $page_size) as $post) { ?>
			<div class="postDiv <?php echo $i++ % 2 ? 'even' : 'odd' ?>">
				<?php if ($post->type == Entry::$TYPE_POST) { ?>
					<h3 class="postTitle">
						<span><?php echo $post->title ?></span>
						<span>(Posted by <a href="profile/<?php echo User::getUser($entry->userref)->id ?>" target="_blank"><?php echo User::getUser($entry->userref)->nickname ?></a>
							on <?php echo $entry->getFormattedDate('modified', L10n::$date_format) ?>)</span>
						<?php
							if (UserManager::getUser()->getRights($post)->may_edit) {
								?><span><a href="javascript:Fuji.newEditDialog('<?php echo $post->id ?>')"><?php echo L10n::$edit ?></a></span><?php
							}
							if (UserManager::getUser()->getRights($post)->may_delete) {
								?><span><a href="javascript:Fuji.newDeleteDialog('<?php echo $post->id ?>', '<?php echo $post->ref ?>')"><?php echo L10n::$delete ?></a></span><?php
							}
						?>
					</h3>
					<div class="postContent"><?php echo $post->getProcessedContent() ?></div>
					<?php /*
						if (UserManager::getUser()->getRights($post)->may_edit || UserManager::getUser()->getRights($post)->may_delete) {
						?><ul class="menu"><li>Actions:<ul><?php
							if (UserManager::getUser()->getRights($post)->may_edit) {
								?><span><a href="javascript:Fuji.newEditDialog('<?php echo $post->id ?>')"><?php echo L10n::$edit ?></a></span><?php
							}
							if (UserManager::getUser()->getRights($post)->may_delete) {
								?><span><a href="javascript:Fuji.newDeleteDialog('<?php echo $post->id ?>', '<?php echo $post->ref ?>')"><?php echo L10n::$delete ?></a></span><?php
							}
						?></ul></li></ul><?php
						}*/
					?>
				
				<?php } else { ?>
				
					(<?php echo L10n::$type_unsupported ?>)
				
				<?php } ?>
			</div>
			<?php } else echo L10n::$error_no_posts ?>
		</div>
		<div class="threadPageSelector pager">
			<?php echo L10n::$page?>&#160;
			<?php if($page > 1) { ?><a href="#item/<?php echo $entry->id ?>/<?php echo $page - 1 ?>">&lt;&#160;</a><?php } ?>
			<?php for($n = 1; $n <= max(0, $num_of_children - 1) / $page_size + 1; $n++) { if($n != $page) { ?><a href="#item/<?php echo $entry->id ?>/<?php echo $n ?>"><?php echo $n ?></a><?php } else { echo $n; } ?>&#160;<?php } ?>
			<?php if(max(0, $num_of_children - 1) / $page_size > $page) { ?><a href="#item/<?php echo $entry->id ?>/<?php echo $page + 1 ?>">&gt;</a><?php } ?>		
		</div>
		</div>
	</fuji:content>
</fuji:item>