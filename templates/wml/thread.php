<?php echo $template->getPrefix() ?>
<!DOCTYPE wml PUBLIC "-//WAPFORUM//DTD WML 1.1//EN" "http://www.wapforum.org/DTD/wml_1.1.xml">
<wml>
	<card>
		<p><b><?php echo $entry->title ?></b><br />
		<?php echo $entry->getProcessedContents() ?><br />
		<?php echo L10n::$posted_by ?> <?php echo User::getUser($entry->userref)->nickname ?> <?php echo L10n::$at ?> <?php echo $entry->getFormattedDate('modified', L10n::$date_format) ?></p>
		<?php $num_of_children = count($entry->getChildren());	
				if($num_of_children > 0)
					foreach($entry->getChildren('created', ($page - 1) * 3 . ', 3') as $post) { ?>
						<p><b><?php echo $post->title ?></b><br />
						<?php echo $post->getProcessedContent() ?><br />
						<?php echo L10n::$posted_by ?> <?php echo User::getUser($post->userref)->nickname ?> <?php echo L10n::$at ?> <?php echo $post->getFormattedDate('modified', L10n::$date_format) ?></p>
		<?php } ?>
		<p><?php if($page > 1) { ?><a href="../<?php echo $entry->id ?>/<?php echo $page - 1 ?>">&lt; <?php echo L10n::$previous ?></a>&nbsp;<?php } ?>
		<?php if($num_of_children > $page * 3) { ?><a href="../<?php echo $entry->id ?>/<?php echo $page + 1 ?>"><?php echo L10n::$next ?> &gt;</a><?php } ?></p>
		<p><a href="../<?php echo $entry->getParent()->id ?>/"><?php echo L10n::$back ?></a></p>
		<small>Powered by Fuji</small>
	</card>
</wml>