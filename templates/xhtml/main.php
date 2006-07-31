<?php echo $this->getPrefix() ?>
<!DOCTYPE html
	PUBLIC "-//W3C//DTD XHTML 1.1 plus MathML 2.0//EN"
	"http://www.w3.org/Math/DTD/mathml2/xhtml-math11-f.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->l10n->language ?>">
<head>
	<title><?php echo $this->entry->title ?></title>
	<link rel="alternate" type="application/atom+xml" title="Atom" href="/blog/atom.php" />
	<link rel="alternate" type="application/rss+xml" title="RSS" href="/blog/rss.php" />
	<link rel="meta" type="application/rdf+xml" title="FOAF" href="foaf.rdf" />
	<link rel="icon" type="image/png" href="/images/siteicon.png" />
	<?php echo $this->getHead() ?>
</head>
<body>
	<?php echo $this->getHeader() ?>
	
	<h1><?php echo $this->entry->title ?></h1>
	
	<?php echo $this->entry->getProcessedContent() ?>
	
	<?php recur($this->entry) ?>
	<?php function recur($entry) { ?>
	<ul>
		<?php foreach($entry->getChildren() as $post) { ?>
			<?php if ($post->type == Entry::$TYPE_FORUMCONTAINER) { ?>
			<li>
				
				<h2><?php echo $post->title ?></h2>
				
				<?php echo $post->getProcessedContent() ?>
				
				<?php recur($post) ?>
				
			</li>
			<?php } ?>
			<?php if ($post->type == Entry::$TYPE_FORUM) { ?>
			<li>
				
				<h2><a href="../<?php echo $post->getURI() ?>"><?php echo $post->title ?></a></h2>
				
				<?php echo $post->getProcessedContent() ?>
				
			</li>
			<?php } ?>
		<?php } ?>
	</ul>
	<?php } ?>
	
	<?php echo $this->getFooter() ?>
</body>
</html>