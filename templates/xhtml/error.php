<?php echo $this->getPrefix() ?>
<!DOCTYPE html
	PUBLIC "-//W3C//DTD XHTML 1.1 plus MathML 2.0//EN"
	"http://www.w3.org/Math/DTD/mathml2/xhtml-math11-f.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->l10n->language ?>">
<head>
	<title><?php echo $entry->title ?></title>
	<link rel="alternate" type="application/atom+xml" title="Atom" href="/blog/atom.php" />
	<link rel="alternate" type="application/rss+xml" title="RSS" href="/blog/rss.php" />
	<link rel="meta" type="application/rdf+xml" title="FOAF" href="foaf.rdf" />
	<link rel="icon" type="image/png" href="/images/siteicon.png" />
	<?php echo $this->getHead() ?>
</head>
<body>
	<?php echo $this->getHeader() ?>
	<p><?php echo $this->l10n->error_insufficient_rights ?></p>
	<?php echo $this->getFooter() ?>
</body>
</html>