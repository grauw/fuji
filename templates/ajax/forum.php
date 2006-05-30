<?php echo $template->getPrefix() ?>
<!DOCTYPE html
	PUBLIC "-//W3C//DTD XHTML 1.1 plus MathML 2.0//EN"
	"http://www.w3.org/Math/DTD/mathml2/xhtml-math11-f.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo L10n::$language ?>">
<head>
	<title><?php echo $entry->title ?></title>
	<link rel="alternate" type="application/atom+xml" title="Atom" href="/blog/atom.php" />
	<link rel="alternate" type="application/rss+xml" title="RSS" href="/blog/rss.php" />
	<link rel="meta" type="application/rdf+xml" title="FOAF" href="foaf.rdf" />
	<link rel="icon" type="image/png" href="/images/siteicon.png" />
	<?php echo $template->getHead() ?>
</head>
<body>
	<?php echo $template->getHeader() ?>
	<h1 default="<?php echo L10n::$threads ?>"><?php echo $entry->title ?></h1>
	<div id="threadsTableDiv" may_post="<?php echo UserManager::getUser()->getRights($entry)->may_post ?>" may_edit="<?php echo UserManager::getUser()->getRights($entry)->may_edit ?>" may_delete="<?php echo UserManager::getUser()->getRights($entry)->may_delete ?>">
		<div class="forumPostDiv">
				<div class="postTitle">
					<span><?php echo $entry->title ?></span>
					<span><?php echo User::getUser($entry->userref)->nickname ?></span>
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
					<td><a href="javascript:Fuji.loadThreads(<?php echo $post->id ?>)"><?php echo $post->title ?></a></td>
					<td><?php echo User::getUser($post->userref)->nickname ?></td>
					<td><?php echo count($post->getChildren()) ?></td>
					<td><?php echo $post->modified ?></td>
				
				<?php } else if ($post->type == Entry::$TYPE_POST) { ?>
				<tr entryid="<?php echo $post->id ?>" entrytitle="<?php echo $post->title ?>" class="tableThreadEntry">				
					<td><a href="javascript:Fuji.loadPosts(<?php echo $post->id ?>)"><?php echo $post->title ?></a></td>
					<td><?php echo User::getUser($post->userref)->nickname ?></td>
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
	<?php echo $template->getFooter() ?>
</body>
</html>