<?php echo $this->getPrefix() ?>
<fuji:rights xmlns="http://www.w3.org/1999/xhtml"
	xmlns:fuji="http://fuji.sourceforge.net/ns"
	xml:lang="<?php echo $this->l10n->language ?>"
	fuji:itemid="<?php echo $this->entry->id ?>">
	<?php foreach($this->entry->getAllRights() as $rights) { ?>
	<fuji:group fuji:groupid="<?php echo $rights->groupid ?>" fuji:groupname="<?php echo Group::getGroup($rights->groupid)->name ?>">
	    <fuji:read fuji:allowed="<?php echo $rights->may_read ?>" />
	    <fuji:post fuji:allowed="<?php echo $rights->may_post ?>" />
	    <fuji:edit fuji:allowed="<?php echo $rights->may_edit ?>" />
	    <fuji:delete fuji:allowed="<?php echo $rights->may_delete ?>" />
	</fuji:group>
	<?php } ?>
</fuji:rights>
