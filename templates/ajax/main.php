<?php echo $template->getPrefix() ?>
<fuji:item xmlns="http://www.w3.org/1999/xhtml"
	xmlns:fuji="http://fuji.sourceforge.net/ns"
	xml:lang="<?php echo L10n::$language ?>"
	loadcontext="foraDiv">
	<fuji:loadhandler>
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
								$title = htmlentities($fora[$n]->title, ENT_NOQUOTES);
								$id = $fora[$n]->id;
								echo "var child$id = new YAHOO.widget.TextNode({label:\"$title\", href:\"#item/$id/\"}, $parent, false);\n";
								addChildren($fora[$n], "child$id");
							}
						}
					}
					addChildren($entry);
				?>
				tree.draw();
				tree.expandAll();
			}
		}
		
		Fuji.ForumTree.treeLoad();
	</fuji:loadhandler>
	<fuji:content>
		<h2 id="foraTitle" class="windowTitle"><?php echo L10n::$fora ?></h2>
		<div id="forumTreeDiv">
			<div id="forumTree"></div>
		</div>
	</fuji:content>
</fuji:item>