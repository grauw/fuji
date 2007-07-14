<?php echo $this->getPrefix(); ?>
<!DOCTYPE wml PUBLIC "-//WAPFORUM//DTD WML 1.1//EN" "http://www.wapforum.org/DTD/wml_1.1.xml">
<wml>
	<card>
		<p><b><?php echo htmlentities($this->entry->title, ENT_NOQUOTES) ?></b><br />
		<?php echo $this->entry->getProcessedContent() ?></p>
		<p>
		<?php $num_of_children = count($this->entry->getChildren());	
				if($num_of_children > 0)
					foreach($this->entry->getChildren('type, created DESC', ($this->page - 1) * 5 . ', 5') as $post) { ?>
						<a href="../../<?php echo $post->getURI() ?>/"><?php echo htmlentities($post->title, ENT_NOQUOTES) ?></a>, <?php echo htmlentities(User::getUser($post->userref)->nickname, ENT_NOQUOTES) ?>, <?php echo $post->getFormattedDate('modified', $this->l10n->datetime_format) ?><br />
		<?php } ?>
		</p>
		<p><?php if($this->page > 1) { ?><a href="../../<?php echo $this->entry->getURI() ?>/<?php echo $this->page - 1 ?>">&lt; <?php echo $this->l10n->previous ?></a>&nbsp;<?php } ?>
		<?php if($num_of_children > $this->page * 5) { ?><a href="../../<?php echo $this->entry->getURI() ?>/<?php echo $this->page + 1 ?>"><?php echo $this->l10n->next ?> &gt;</a><?php } ?></p>		
		<p><a href="<?php echo $this->entry->getParent()->id == 1 ? '../..' : '../../' . $this->entry->getParent()->getURI() ?>/"><?php echo $this->l10n->back ?></a></p>
		<small>Powered by Fuji</small>
	</card>
</wml>