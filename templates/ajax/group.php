<?php echo $this->getPrefix() ?>
<fuji:group xmlns="http://www.w3.org/1999/xhtml"
	xmlns:fuji="http://fuji.sourceforge.net/ns"
	xml:lang="<?php echo $this->l10n->language ?>"
	fuji:groupid="<?php echo $this->group->id ?>"
	fuji:groupname="<?php echo $this->group->name ?>">
	<?php foreach($this->group->getUsers() as $user) { ?>
	<fuji:user fuji:userid="<?php echo $user->id ?>" fuji:username="<?php echo $user->nickname ?>" />
	<?php } ?>
</fuji:group>
