<?php echo $template->getPrefix() ?>
<fuji:item xmlns="http://www.w3.org/1999/xhtml"
	xmlns:fuji="http://fuji.sourceforge.net/ns"
	xml:lang="<?php echo L10n::$language ?>"
	loadcontext="threadsDiv"
	parent="item/1">
	<fuji:content>
		<h2 class="windowTitle"><?php echo L10n::$threads ?> - <?php echo $entry->title ?></h2>
		<div id="threadPath" class="windowInfo"></div> 
		<div id="threadsActions" class="windowActions">
			<?php
				if (UserManager::getUser()->getRights($entry)->may_post) {
					?><span id="actionNewThread"><a href="javascript:Fuji.newCreateDialog('<?php echo $entry->id ?>', '4')"><?php echo L10n::$new ?></a></span><?php
				}
				if (UserManager::getUser()->getRights($entry)->may_edit) {
					?><span id="actionEditThread"><a href="javascript:Fuji.newEditDialog('<?php echo $entry->id ?>')"><?php echo L10n::$edit ?></a></span><?php
				}
				if (UserManager::getUser()->getRights($entry)->may_delete) {
					?><span id="actionDeleteThread"><a href="javascript:Fuji.newDeleteDialog('<?php echo $entry->id ?>')"><?php echo L10n::$delete ?></a></span><?php
				}
			?>
			<!--<span><?php echo L10n::$search ?></span>-->
			<span><a href="javascript:Fuji.reloadItem('item/<?php echo $entry->id ?>')"><?php echo L10n::$refresh ?></a></span>
		</div>
		<div id="threadsTableDiv">
			<div id="threadsTableDiv" may_post="<?php echo UserManager::getUser()->getRights($entry)->may_post ?>" may_edit="<?php echo UserManager::getUser()->getRights($entry)->may_edit ?>" may_delete="<?php echo UserManager::getUser()->getRights($entry)->may_delete ?>">
				<div class="forumPostDiv">
						<div class="postTitle">
							<span><?php echo $entry->title ?></span>
							<span><a href="profile/<?php echo User::getUser($entry->userref)->id ?>" target="_blank"><?php echo User::getUser($entry->userref)->nickname ?></a></span>
							<span><?php echo $entry->modified ?></span>
						</div>
						<div class="postContent"><?php echo $entry->getContent() ?></div>
				</div>	
			<table id="threadsTable">
				<thead>
					<tr>
						<td><?php echo L10n::$topic ?></td>
						<td><?php echo L10n::$poster ?></td>
						<td><?php echo L10n::$replies ?></td>
						<td><?php echo L10n::$modified ?></td>
					</tr>
				</thead>
				<tbody>
				<?php 	if(count($entry->getChildren()) > 0)
						foreach($entry->getChildren() as $post) { ?>
						<?php if ($post->type == Entry::$TYPE_FORUM) { ?>
						<tr entryid="<?php echo $post->id ?>" entrytitle="<?php echo $post->title ?>" class="tableForumEntry">
							<td><a href="#item/<?php echo $post->id ?>"><?php echo $post->title ?></a></td>
							<td><a href="profile/<?php echo User::getUser($entry->userref)->id ?>" target="_blank"><?php echo User::getUser($entry->userref)->nickname ?></a></td>
							<td><?php echo count($post->getChildren()) ?></td>
							<td><?php echo $post->modified ?></td>
						
						<?php } else if ($post->type == Entry::$TYPE_POST) { ?>
						<tr entryid="<?php echo $post->id ?>" entrytitle="<?php echo $post->title ?>" class="tableThreadEntry">				
							<td><a href="#item/<?php echo $post->id ?>"><?php echo $post->title ?></a></td>
							<td><a href="profile/<?php echo User::getUser($entry->userref)->id ?>" target="_blank"><?php echo User::getUser($entry->userref)->nickname ?></a></td>
							<td><?php echo count($post->getChildren()) ?></td>
							<td><?php echo $post->modified ?></td>
						
						<?php } else { ?>
						<tr entryid="<?php echo $post->id ?>" entrytitle="<?php echo $post->title ?>" class="tableUnsupportedEntry">				
							(<?php echo L10n::$type_unsupported ?>)
						
						<?php } ?>
					</tr>
				<?php } else echo L10n::$error_no_threads ?>
				</tbody>
			</table>
			</div>
		</div>
	</fuji:content>
</fuji:item>