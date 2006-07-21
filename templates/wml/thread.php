<?php echo $this->getPrefix() ?>
<!DOCTYPE wml PUBLIC "-//WAPFORUM//DTD WML 1.1//EN" "http://www.wapforum.org/DTD/wml_1.1.xml">
<wml>
	<card>
		<p><b><?php echo htmlentities($entry->title, ENT_NOQUOTES) ?></b><br />
		<?php echo $entry->getProcessedContent() ?><br />
		<?php echo L10n::$posted_by ?> <?php echo htmlentities(User::getUser($entry->userref)->nickname, ENT_NOQUOTES) ?> <?php echo L10n::$on ?> <?php echo $entry->getFormattedDate('modified', L10n::$date_format) ?></p>
		<?php $num_of_children = count($entry->getChildren());	
				if($num_of_children > 0)
					foreach($entry->getChildren('created', ($page - 1) * 3 . ', 3') as $post) { ?>
						<p><b><?php echo htmlentities($post->title, ENT_NOQUOTES) ?></b><br />
						<?php echo $post->getProcessedContent() ?><br />
						<?php echo L10n::$posted_by ?> <?php echo htmlentities(User::getUser($post->userref)->nickname, ENT_NOQUOTES) ?> <?php echo L10n::$on ?> <?php echo $post->getFormattedDate('modified', L10n::$date_format) ?></p>
		<?php } ?>
		<p><?php if($page > 1) { ?><a href="../../<?php echo $entry->getURI() ?>/<?php echo $page - 1 ?>">&lt; <?php echo L10n::$previous ?></a>&nbsp;<?php } ?>
		<?php if($num_of_children > $page * 3) { ?><a href="../../<?php echo $entry->getURI() ?>/<?php echo $page + 1 ?>"><?php echo L10n::$next ?> &gt;</a><?php } ?></p>
		<p><a href="<?php echo $entry->getParent()->id == 1 ? '../..' : '../../' . $entry->getParent()->getURI() ?>/"><?php echo L10n::$back ?></a></p>
		<small>Powered by Fuji</small>
	</card>
</wml>