<?php echo $this->getPrefix() ?>
<fuji:item xmlns="http://www.w3.org/1999/xhtml"
	xmlns:fuji="http://fuji.sourceforge.net/ns"
	xml:lang="<?php echo $this->l10n->language ?>"
	loadcontext="foraDiv">
	<fuji:loadhandler function="Fuji.Tree.init"/>
	<fuji:content>
		<h2><?php echo $this->l10n->fora ?></h2>
		<div class="content">
			<?php
				function addChildren($entry, $root=true)
				{
					$forumchildren = $entry->getChildren();
					$forums = array();
					foreach ($forumchildren as $forum) {
						if ($forum->type < 4)
							array_push($forums, $forum);
					}
					$rv = '';
					if (count($forums) > 0) {
						$rv .= '<ul'.($root ? ' class="tree"' : '').">\n";
						foreach ($forums as $forum) {
							$children = addChildren($forum, false);
							if ($children == '') {
								$rv .= '<li class="leaf">';
							} else {
								$rv .= '<li class="open">';
							}
							$rv .= '<a href="#'.$forum->getURI().'">'.htmlentities($forum->title, ENT_NOQUOTES).'</a>';
							$rv .= $children;
							$rv .= "</li>\n";
						}
						$rv .= "</ul>\n";
					}
					return $rv;
				}
				echo addChildren($this->entry);
			?>
		</div>
	</fuji:content>
</fuji:item>