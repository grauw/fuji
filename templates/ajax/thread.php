<?php echo $template->getPrefix() ?>
<fuji:item xmlns="http://www.w3.org/1999/xhtml"
	xmlns:fuji="http://fuji.sourceforge.net/ns"
	xml:lang="<?php echo L10n::$language ?>"
	loadcontext="postsDiv"
	parent="item/<?php echo $entry->getParent()->id ?>">
	<fuji:content>
		<h2 class="windowTitle"><?php echo L10n::$posts ?> - <?php echo $entry->title ?></h2>
		<div id="postsActions" class="windowActions">
			<?php
				if (UserManager::getUser()->getRights($entry)->may_post) {
					?><span id="actionReplyPost"><a href="javascript:Fuji.showReplyDialog('<?php echo $entry->id ?>')"><?php echo L10n::$reply ?></a></span><?php
				}
				if (UserManager::getUser()->getRights($entry)->may_edit) {
					?><span id="actionEditPost"><?php echo L10n::$edit ?></span><?php
				}
				if (UserManager::getUser()->getRights($entry)->may_delete) {
					?><span id="actionDeletePost"><a href="javascript:Fuji.deleteDialog('<?php echo $entry->id ?>')"><?php echo L10n::$delete ?></a></span><?php
				}
			?>
			<span><?php echo L10n::$search ?></span>
			<span><a href="javascript:Fuji.reloadItem('item/<?php echo $entry->id ?>')"><?php echo L10n::$refresh ?></a></span>
		</div>
		<div id="postsListDiv">
			<div class="postDiv">
					<h3 class="postTitle">
						<span><?php echo $entry->title ?></span>
						<span><a href="profile/<?php echo User::getUser($entry->userref)->id ?>" target="_blank"><?php echo User::getUser($entry->userref)->nickname ?></a></span>
						<span><?php echo $entry->modified ?></span>
						<span><a href="javascript:Fuji.showEditPostDialog('<?php echo $entry->id ?>')"><?php echo L10n::$edit ?></a></span>
						<span><a href="javascript:Fuji.deleteDialog('<?php echo $entry->id ?>')"><?php echo L10n::$delete ?></a></span>
					</h3>
					<div class="postContent"><?php echo $entry->getContent() ?></div>
			</div>
			<?php 	if(count($entry) > 0)
					foreach($entry->getChildren() as $post) { ?>
			<div class="postDiv">
				<?php if ($post->type == Entry::$TYPE_POST) { ?>
					<h4 class="postTitle">
						<span><?php echo $post->title ?></span>
						<span><a href="profile/<?php echo User::getUser($entry->userref)->id ?>" target="_blank"><?php echo User::getUser($entry->userref)->nickname ?></a></span>
						<span><?php echo $post->modified ?></span>
						<?php
							if (UserManager::getUser()->getRights($post)->may_edit) {
								?><span><a href="javascript:Fuji.showEditPostDialog('<?php echo $post->id ?>')"><?php echo L10n::$edit ?></a></span><?php
							}
							if (UserManager::getUser()->getRights($post)->may_delete) {
								?><span><a href="javascript:Fuji.deleteDialog('<?php echo $post->id ?>')"><?php echo L10n::$delete ?></a></span><?php
							}
						?>
					</h4>
					<div class="postContent"><?php echo $post->getContent() ?></div>
				
				<?php } else { ?>
				
					(<?php echo L10n::$type_unsupported ?>)
				
				<?php } ?>
			</div>
			<?php } else echo L10n::$error_no_posts ?>
		</div>
	</fuji:content>
</fuji:item>