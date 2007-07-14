<?php echo $this->getPrefix(); ?>
<!DOCTYPE wml PUBLIC "-//WAPFORUM//DTD WML 1.1//EN" "http://www.wapforum.org/DTD/wml_1.1.xml">
<wml>
	<card>
		<p><b><?php echo htmlentities($this->entry->title, ENT_NOQUOTES) ?></b><br />
		<?php echo $this->entry->getProcessedContent() ?><br />
		<?php echo $this->l10n->posted_by ?> <?php echo htmlentities(User::getUser($this->entry->userref)->nickname, ENT_NOQUOTES) ?> <?php echo $this->l10n->on ?> <?php echo $this->entry->getFormattedDate('modified', $this->l10n->datetime_format) ?></p>
		<?php $num_of_children = count($this->entry->getChildren());	
				if($num_of_children > 0)
					foreach($this->entry->getChildren('created', ($this->page - 1) * 3 . ', 3') as $post) { ?>
						<p><b><?php echo htmlentities($post->title, ENT_NOQUOTES) ?></b><br />
						<?php echo $post->getProcessedContent() ?><br />
						<?php echo $this->l10n->posted_by ?> <?php echo htmlentities(User::getUser($post->userref)->nickname, ENT_NOQUOTES) ?> <?php echo $this->l10n->on ?> <?php echo $post->getFormattedDate('modified', $this->l10n->datetime_format) ?></p>
		<?php } ?>
		<p><?php if($this->page > 1) { ?><a href="../../<?php echo $this->entry->getURI() ?>/<?php echo $this->page - 1 ?>">&lt; <?php echo $this->l10n->previous ?></a>&nbsp;<?php } ?>
		<?php if($num_of_children > $this->page * 3) { ?><a href="../../<?php echo $this->entry->getURI() ?>/<?php echo $this->page + 1 ?>"><?php echo $this->l10n->next ?> &gt;</a><?php } ?></p>
		<p><a href="<?php echo $this->entry->getParent()->id == 1 ? '../..' : '../../' . $this->entry->getParent()->getURI() ?>/"><?php echo $this->l10n->back ?></a></p>
		<small>Powered by Fuji</small>
	</card>
</wml>