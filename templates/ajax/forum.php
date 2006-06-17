<?php $page_size = 30; ?>
<?php echo $template->getPrefix() ?>
<fuji:item xmlns="http://www.w3.org/1999/xhtml"
	xmlns:fuji="http://fuji.sourceforge.net/ns"
	xml:lang="<?php echo L10n::$language ?>"
	loadcontext="threadsDiv"
	parent="item/1/">
	<fuji:content>
		<h2 class="windowTitle"><?php echo L10n::$threads ?> - <?php echo $entry->title ?></h2>
		<div id="threadPath" class="windowInfo"></div> 
		<div id="threadsActions" class="windowActions">
			<?php
				if (UserManager::getUser()->getRights($entry)->may_post) {
					?><span id="actionNewThread"><a href="javascript:Fuji.newCreateDialog('<?php echo $entry->id ?>', '<?php echo $entry->type + 1 ?>')"><?php echo L10n::$new ?></a></span><?php
				}
				if (UserManager::getUser()->getRights($entry)->may_edit) {
					?><span id="actionEditThread"><a href="javascript:Fuji.newEditDialog('<?php echo $entry->id ?>')"><?php echo L10n::$edit ?></a></span><?php
				}
				if (UserManager::getUser()->getRights($entry)->may_delete) {
					?><span id="actionDeleteThread"><a href="javascript:Fuji.newDeleteDialog('<?php echo $entry->id ?>', '<?php echo $entry->ref ?>')"><?php echo L10n::$delete ?></a></span><?php
				}
			?>
			<!--<span><?php echo L10n::$search ?></span>-->
			<span><a type="application/rss+xml" href="rss/<?php echo $entry->id ?>"><img style="vertical-align: middle" src="style/default/graphics/feed-icon.gif" border="0" alt="<?php echo L10n::$subscribe_to_rss ?>" /></a><a type="application/rss+xml" href="rss/<?php echo $entry->id ?>">&#160;RSS</a></span>
			<span><a type="application/atom+xml" href="atom/<?php echo $entry->id ?>"><img style="vertical-align: middle" src="style/default/graphics/feed-icon.gif" border="0" alt="<?php echo L10n::$subscribe_to_atom ?>" /></a><a type="application/atom+xml" href="atom/<?php echo $entry->id ?>">&#160;Atom</a></span>			
		</div>
		<div id="threadsTableDiv">
			<div id="threadsTableDiv" may_post="<?php echo UserManager::getUser()->getRights($entry)->may_post ?>" may_edit="<?php echo UserManager::getUser()->getRights($entry)->may_edit ?>" may_delete="<?php echo UserManager::getUser()->getRights($entry)->may_delete ?>">
				<div class="forumPostDiv">
						<div class="postTitle">
							<span><?php echo $entry->title ?></span>
							<span><a href="profile/<?php echo User::getUser($entry->userref)->id ?>" target="_blank"><?php echo User::getUser($entry->userref)->nickname ?></a></span>
							<span><?php echo $entry->getFormattedDate('modified', L10n::$date_format) ?></span>
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
				<?php 	$num_of_children = count($entry->getChildren());
						if($num_of_children > 0)
						foreach($entry->getChildren('type, modified DESC', ($page - 1) * $page_size . ', ' . $page_size) as $post) { ?>
						<?php if ($post->type == Entry::$TYPE_FORUM) { ?>
						<tr entryid="<?php echo $post->id ?>" entrytitle="<?php echo $post->title ?>" class="tableForumEntry">
							<td><a href="#item/<?php echo $post->id ?>/"><?php echo $post->title ?></a></td>
							<td><a href="profile/<?php echo User::getUser($entry->userref)->id ?>" target="_blank"><?php echo User::getUser($entry->userref)->nickname ?></a></td>
							<td><?php echo count($post->getChildren()) ?></td>
							<td><?php echo $post->getFormattedDate('modified', L10n::$date_format) ?></td>
						
						<?php } else if ($post->type == Entry::$TYPE_POST) { ?>
						<tr entryid="<?php echo $post->id ?>" entrytitle="<?php echo $post->title ?>" class="tableThreadEntry">				
							<td><a href="#item/<?php echo $post->id ?>/"><?php echo $post->title ?></a></td>
							<td><a href="profile/<?php echo User::getUser($entry->userref)->id ?>" target="_blank"><?php echo User::getUser($entry->userref)->nickname ?></a></td>
							<td><?php echo count($post->getChildren()) ?></td>
							<td><?php echo $post->getFormattedDate('modified', L10n::$date_format) ?><?php 
								if (UserManager::isAdmin()) 
								{ ?><a href="javascript:Fuji.newConfigRightsDialog('<?php echo $entry->id ?>')"><?php echo L10n::$config_rights ?></a><?php } ?>
							</td>
						<?php } else { ?>
						<tr entryid="<?php echo $post->id ?>" entrytitle="<?php echo $post->title ?>" class="tableUnsupportedEntry">				
							(<?php echo L10n::$type_unsupported ?>)
						
						<?php } ?>
					</tr>
				<?php } else echo L10n::$error_no_threads ?>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="4">
							<?php echo L10n::$page?>&#160;
							<?php if($page > 1) { ?><a href="#item/<?php echo $entry->id ?>/<?php echo $page - 1 ?>">&lt;&#160;</a><?php } ?>
							<?php for($n = 1; $n <= $num_of_children / $page_size + 1; $n++) { if($n != $page) { ?><a href="#item/<?php echo $entry->id ?>/<?php echo $n ?>"><?php echo $n ?></a><?php } else { echo $n; } ?>&#160;<?php } ?>
							<?php if($num_of_children / $page_size > $page) { ?><a href="#item/<?php echo $entry->id ?>/<?php echo $page + 1 ?>">&gt;</a><?php } ?>
						</td>
					</tr>
				</tfoot>
			</table>
			</div>
		</div>
		<div id="item/<?php echo $entry->id ?>_postsDiv" class="postsContainer"></div>
	</fuji:content>
</fuji:item>