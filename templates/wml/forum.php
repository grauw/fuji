<?php echo $template->getPrefix() ?>
<!DOCTYPE wml PUBLIC "-//WAPFORUM//DTD WML 1.1//EN" "http://www.wapforum.org/DTD/wml_1.1.xml">
<wml>
	<card>
		<p><b><?php echo htmlentities($entry->title, ENT_NOQUOTES) ?></b><br />
		<?php echo $entry->getProcessedContent() ?></p>
		<p>
		<?php $num_of_children = count($entry->getChildren());	
				if($num_of_children > 0)
					foreach($entry->getChildren('type, created DESC', ($page - 1) * 5 . ', 5') as $post) { ?>
						<a href="../<?php echo $post->id ?>/"><?php echo htmlentities($post->title, ENT_NOQUOTES) ?></a>, <?php echo htmlentities(User::getUser($post->userref)->nickname, ENT_NOQUOTES) ?>, <?php echo $post->getFormattedDate('modified', L10n::$date_format) ?><br />
		<?php } ?>
		</p>
		<p><?php if($page > 1) { ?><a href="../<?php echo $entry->id ?>/<?php echo $page - 1 ?>">&lt; <?php echo L10n::$previous ?></a>&nbsp;<?php } ?>
		<?php if($num_of_children > $page * 5) { ?><a href="../<?php echo $entry->id ?>/<?php echo $page + 1 ?>"><?php echo L10n::$next ?> &gt;</a><?php } ?></p>		
		<p><a href="<?php echo $entry->getParent()->id == 1 ? '..' : '../' . $entry->getParent()->id ?>/"><?php echo L10n::$back ?></a></p>
		<small>Powered by Fuji</small>
	</card>
</wml>