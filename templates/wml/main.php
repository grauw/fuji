<?php echo $template->getPrefix() ?>
<!DOCTYPE wml PUBLIC "-//WAPFORUM//DTD WML 1.1//EN" "http://www.wapforum.org/DTD/wml_1.1.xml">
<wml>
	<card>
		<p><b><?php echo htmlentities($entry->title, ENT_NOQUOTES) ?></b><br />
		<?php echo $entry->getProcessedContent() ?></p>
		<p>
		<?php 	if(count($entry) > 0)
					foreach($entry->getChildren() as $post) { ?>
						<a href="<?php echo $post->getURI() ?>"><?php echo htmlentities($post->title, ENT_NOQUOTES) ?></a><br />
		<?php } ?>
		</p>
		<small>Powered by Fuji</small>
	</card>
</wml>