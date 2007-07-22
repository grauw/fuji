<?php echo $this->getPrefix() ?>
<fuji:users xmlns="http://www.w3.org/1999/xhtml"
	xmlns:fuji="http://fuji.sourceforge.net/ns"
	xml:lang="<?php echo $this->l10n->language ?>">
	<?php foreach($this->users as $user) { ?>
	<fuji:user fuji:userid="<?php echo $user->id ?>" fuji:username="<?php echo $user->nickname ?>" />
	<?php } ?>
</fuji:users>
