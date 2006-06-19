<?php echo $template->getPrefix() ?>
<!DOCTYPE wml PUBLIC "-//WAPFORUM//DTD WML 1.1//EN" "http://www.wapforum.org/DTD/wml_1.1.xml">
<wml>
	<card>
		<p><b><?php echo $entry->title ?></b><br />
		<?php echo $entry->getProcessedContent() ?></p>
		<p>
		<?php 	if(count($entry) > 0)
					foreach($entry->getChildren() as $post) { ?>
						<a href="../wml/<?php echo $post->id ?>/"><?php echo $post->title ?></a><br />
		<?php } ?>
		</p>
		<small>Powered by Fuji</small>
	</card>
</wml>