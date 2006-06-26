<?php $page_size = 8; ?>
<?php echo $template->getPrefix() ?>
<fuji:item xmlns="http://www.w3.org/1999/xhtml"
	xmlns:fuji="http://fuji.sourceforge.net/ns"
	xml:lang="<?php echo L10n::$language ?>"
	loadcontext="threadsDiv"
	parent="item/1">
	<fuji:content>
		<div class="forumContainer box">
		<h2 class="windowTitle"><?php echo L10n::$threads ?> – <?php echo htmlentities($entry->title, ENT_NOQUOTES) ?></h2>
		<ul id="threadsActions" class="windowActions menu">
			<?php if (UserManager::getUser()->getRights($entry)->may_post) { ?>
				<li id="actionNewThread"><a href="javascript:Fuji.newCreateDialog('<?php echo $entry->id ?>', '<?php echo $entry->type + 1 ?>')"><?php echo L10n::$new ?></a></li>
			<?php } if (UserManager::getUser()->getRights($entry)->may_edit) { ?>
				<li id="actionEditThread"><a href="javascript:Fuji.newEditDialog('<?php echo $entry->id ?>')"><?php echo L10n::$edit ?></a></li>
			<?php } if (UserManager::getUser()->getRights($entry)->may_delete) { ?>
				<li id="actionDeleteThread"><a href="javascript:Fuji.newDeleteDialog('<?php echo $entry->id ?>', '<?php echo $entry->ref ?>')"><?php echo L10n::$delete ?></a></li>
			<?php } if (UserManager::isAdmin()) { ?>
				<li id="actionConfigRights"><a href="#config_rights/<?php echo $entry->id ?>"><?php echo L10n::$config_rights ?></a></li>
			<?php } ?>
			<li><a href="rss/<?php echo $entry->getURI() ?>" rel="alternate" type="application/rss+xml" class="feed">RSS</a></li>
			<li><a href="atom/<?php echo $entry->getURI() ?>" rel="alternate" type="application/atom+xml" class="feed">Atom</a></li>
		</ul>
		<div class="threadsTableDiv content">
			<div may_post="<?php echo UserManager::getUser()->getRights($entry)->may_post ?>" may_edit="<?php echo UserManager::getUser()->getRights($entry)->may_edit ?>" may_delete="<?php echo UserManager::getUser()->getRights($entry)->may_delete ?>">
				<div class="forumPostDiv">
						<div class="postContent"><?php echo $entry->getProcessedContent() ?></div>
				</div>	
			<table class="threadsTable">
				<thead>
					<tr>
						<th><?php echo L10n::$topic ?></th>
						<th><?php echo L10n::$poster ?></th>
						<th><?php echo L10n::$replies ?></th>
						<th><?php echo L10n::$created ?></th>
						<th><?php echo L10n::$modified ?></th>
					</tr>
				</thead>
				<tbody>
				<?php 	$num_of_children = count($entry->getChildren());
						$i = 0;
						if($num_of_children > 0)
						foreach($entry->getChildren('type, created DESC', ($page - 1) * $page_size . ', ' . $page_size) as $post) { ?>
						<?php if ($post->type == Entry::$TYPE_FORUM) { ?>
						<tr class="tableForumEntry <?php echo $i++ % 2 ? 'even' : 'odd' ?>">
							<td><a href="#<?php echo $post->getURI() ?>"><?php echo htmlentities($post->title, ENT_NOQUOTES) ?></a></td>
							<td><a href="#profile/<?php echo User::getUser($post->userref)->id ?>"><?php echo htmlentities(User::getUser($post->userref)->nickname, ENT_NOQUOTES) ?></a></td>
							<td><?php echo count($post->getChildren()) ?></td>
							<td><?php echo $post->getFormattedDate('created', L10n::$date_format) ?></td>
							<td><?php echo $post->getFormattedDate('modified', L10n::$date_format) ?></td>
						
						<?php } else if ($post->type == Entry::$TYPE_POST) { ?>
						<tr class="tableThreadEntry <?php echo $i++ % 2 ? 'even' : 'odd' ?>">				
							<td><a href="#<?php echo $post->getURI() ?>"><?php echo htmlentities($post->title, ENT_NOQUOTES) ?></a></td>
							<td><a href="#profile/<?php echo User::getUser($post->userref)->id ?>"><?php echo htmlentities(User::getUser($post->userref)->nickname, ENT_NOQUOTES) ?></a></td>
							<td><?php echo count($post->getChildren()) ?></td>
							<td><?php echo $post->getFormattedDate('created', L10n::$date_format) ?></td>
							<td><?php echo $post->getFormattedDate('modified', L10n::$date_format) ?></td>
						<?php } else { ?>
						<tr class="tableUnsupportedEntry">				
							(<?php echo L10n::$type_unsupported ?>)
						
						<?php } ?>
					</tr>
				<?php } else echo L10n::$error_no_threads
					// TODO: Invalid XHTML here!
					?>
				</tbody>
			</table>
			</div>
		</div>
		<div class="threadPageSelector pager">
			<?php echo L10n::$page?>
			<?php if($page > 1) { ?><a href="#<?php echo $entry->getURI().'/'.($page-1) ?>">&lt;</a> <?php } ?>
			<?php for($n = 1; $n <= max(0, $num_of_children - 1) / $page_size + 1; $n++) { ?><a href="#<?php echo $entry->getURI().($n>1?'/'.$n:'') ?>"><?php echo $n ?></a> <?php } ?>
			<?php if(max(0, $num_of_children - 1) / $page_size > $page) { ?><a href="#<?php echo $entry->getURI().'/'.($page+1) ?>">&gt;</a> <?php } ?>
		</div>
		</div>
		<div id="<?php echo $entry->getURI() ?>_postsDiv" class="postsContainer"></div>
	</fuji:content>
</fuji:item>