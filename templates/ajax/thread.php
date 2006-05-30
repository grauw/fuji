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
	<h1 default="<?php echo L10n::$posts ?>"><?php echo $entry->title ?></h1>
	
	<div id="postsListDiv" may_post="<?php echo UserManager::getUser()->getRights($entry)->may_post ?>" may_edit="<?php echo UserManager::getUser()->getRights($entry)->may_edit ?>" may_delete="<?php echo UserManager::getUser()->getRights($entry)->may_delete ?>">
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
	
	<?php echo $template->getFooter() ?>
</body>
</html>