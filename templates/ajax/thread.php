<?php echo $template->getPrefix() ?>
<fuji:item xmlns="http://www.w3.org/1999/xhtml"
	xmlns:fuji="http://fuji.sourceforge.net/ns"
	xml:lang="<?php echo L10n::$language ?>"
	fuji:loadcontext="postsDiv">
	<fuji:content>
		<h1 class="windowTitle"><?php echo L10n::$posts ?> - <?php echo $entry->title ?></h1>
		<div id="postsActions" class="windowActions">
			<?php if (UserManager::getUser()->getRights($entry)->may_post) { ?>
			<span id="actionReplyPost"><a href="javascript:Fuji.showReplyDialog()"><?php echo L10n::$reply ?></a></span>
			<?php }
				  if (UserManager::getUser()->getRights($entry)->may_edit) { ?>
			<span id="actionEditPost"><?php echo L10n::$edit ?></span>
			<?php }
				  if (UserManager::getUser()->getRights($entry)->may_delete) { ?>
			<span id="actionDeletePost"><?php echo L10n::$delete ?></span>
			<?php } ?>
			<span><?php echo L10n::$search ?></span>
			<span><a href="javascript:Fuji.reloadPosts()"><?php echo L10n::$refresh ?></a></span>
		</div>
		<div id="postsListDiv">
			<div class="postDiv">
					<div class="postTitle">
						<span><?php echo $entry->title ?></span>
						<span><?php echo User::getUser($entry->userref)->nickname ?></span>
						<span><?php echo $entry->modified ?></span>
					</div>
					<div class="postContent"><?php echo $entry->getContent() ?></div>
			</div>
			<?php 	if(count($entry) > 0)
					foreach($entry->getChildren() as $post) { ?>
			<div class="postDiv">
				<?php if ($post->type == Entry::$TYPE_POST) { ?>
					<div class="postTitle">
						<span><?php echo $post->title ?></span>
						<span><?php echo User::getUser($post->userref)->nickname ?></span>
						<span><?php echo $post->modified ?></span>
					</div>
					<div class="postContent"><?php echo $post->getContent() ?></div>
				
				<?php } else { ?>
				
					(<?php echo L10n::$type_unsupported ?>)
				
				<?php } ?>
			</div>
			<?php } else echo L10n::$error_no_posts ?>
		</div>
	</fuji:content>
</fuji:item>