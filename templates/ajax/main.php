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
	<div id="forumTreeDiv">
	<script type="text/javascript">
		Fuji.ForumTree = {
			treeLoad : function() {
				var tree = new YAHOO.widget.TreeView("forumTree");
				var root = tree.getRoot();
				<?php
					function addChildren($entry, $parent = 'root')
					{
						$fora = $entry->getChildren();
						for($n = 0; $n < count($fora); $n++)
						{
							if($fora[$n]->type < 4)
							{
								$title = $fora[$n]->title;
								$id = $fora[$n]->id;
								echo "var child$id = new YAHOO.widget.TextNode({label:\"$title\", href:\"javascript:Fuji.loadThreads($id)\"}, $parent, false);\n";
								addChildren($fora[$n], "child$id");
							}
						}
					}
					addChildren($entry);
				?>
				tree.draw();
			}
		}
	</script>
	<div id="forumTree"></div>
	</div>
	<?php echo $template->getFooter() ?>
</body>
</html>