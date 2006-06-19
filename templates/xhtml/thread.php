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
	
	<h1><?php echo $entry->title ?></h1>
	
	<?php echo $entry->getProcessedContent() ?>
	
	<ul>
		<?php foreach($entry->getChildren() as $post) { ?>
		<li>
			<?php if ($post->type == Entry::$TYPE_POST) { ?>
			
				<h2><a href="/item/<?php echo $post->id ?>"><?php echo $post->title ?></a></h2>
				
				<?php echo $post->getProcessedContent() ?>
			
			<?php } else { ?>
			
				(<?php echo L10n::$type_unsupported ?>)
			
			<?php } ?>
		</li>
		<?php } ?>
	</ul>
	
	<?php echo $template->getFooter() ?>
</body>
</html>