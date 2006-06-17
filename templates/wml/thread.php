<?php echo $template->getPrefix() ?>
<!DOCTYPE wml PUBLIC "-//WAPFORUM//DTD WML 1.1//EN" "http://www.wapforum.org/DTD/wml_1.1.xml">
<wml>
	<card>
		<p><b><?php echo $entry->title ?></b><br />
		<?php echo $entry->getContent() ?><br />
		<?php echo L10n::$posted_by ?> <?php echo User::getUser($entry->userref)->nickname ?> <?php echo L10n::$at ?> <?php echo $entry->getFormattedDate('modified', L10n::$date_format) ?></p>
		<?php 	if(count($entry) > 0)
					foreach($entry->getChildren('created') as $post) { ?>
						<p><b><?php echo $post->title ?></b><br />
						<?php echo $post->getContent() ?><br />
						<?php echo L10n::$posted_by ?> <?php echo User::getUser($post->userref)->nickname ?> <?php echo L10n::$at ?> <?php echo $post->getFormattedDate('modified', L10n::$date_format) ?></p>
		<?php } ?>
		<p><a href="../wml/<?php echo $entry->getParent()->id ?>"><?php echo L10n::$back ?></a></p>
		<small>Powered by Fuji</small>
	</card>
</wml>