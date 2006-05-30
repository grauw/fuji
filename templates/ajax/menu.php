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
	
	<script type="text/javascript" src="../js/yui/yahoo/yahoo.js"></script>
	<script type="text/javascript" src="../js/yui/treeview/treeview.js"></script>
	<script type="text/javascript">
		Fuji = {}
	</script>
	<?php echo $template->getHead() ?>
</head>
<body onload="Fuji.ForumTree.treeLoad()">
	<?php echo $template->getHeader() ?>	
	<div id="menuItems">
	<?php
		echo '<p>Logged in as ' . UserManager::getUser()->nickname . '</p>';
		echo '<ul>'."\n";
		if(UserManager::getUser()->nickname == 'anonymous'){
			echo '<li><a href="">'.L10n::$login.'</a></li>'."\n";
			echo '<li><a href="scripts/register/">'.L10n::$register.'</a></li>'."\n";
		}else{
			echo '<li><a href="profile.php">'.L10n::$profile.'</a></li>'."\n";
		}
		echo '</ul>'."\n";
	?>
	<ul>
	</ul>
	</div>
	<?php echo $template->getFooter() ?>
</body>
</html>