<?php echo $template->getPrefix() ?>
<feed xmlns="http://www.w3.org/2005/Atom">
	<title><?php echo $entry->title ?></title>
	<link href="<?php echo /*$template->getBaseURL() . */'../#item/' . $entry->id ?>/" />
	<content type="xhtml" xml:lang="<?php echo L10n::$language ?>"><?php echo $entry->getProcessedContent() ?></content>
	<updated><?php echo $entry->getFormattedDate('modified', '%Y-%m-%dT%H:%i:%sZ') ?></updated>
	<author><name><?php echo User::getUser($entry->userref)->nickname ?></name></author>
	<?php 	if(count($entry) > 0)
			foreach($entry->getChildren('created DESC', 10) as $post) { ?>
	<entry>
		<title><?php echo $post->title ?></title>
		<link href="<?php echo /*$template->getBaseURL() . */'../#item/' . $post->id ?>/" />
		<content type="xhtml" xml:lang="<?php echo L10n::$language ?>"><?php echo $post->getProcessedContent() ?></content>
		<author><name><?php echo User::getUser($entry->userref)->nickname ?></name></author>
		<updated><?php echo $post->getFormattedDate('modified', '%Y-%m-%dT%H:%i:%sZ') ?></updated>			
	</entry>
	<?php } ?>
</feed>