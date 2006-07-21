<?php echo $this->getPrefix() ?>
<feed xmlns="http://www.w3.org/2005/Atom">
	<title><?php echo $entry->title ?></title>
	<link href="<?php echo '../#' . $entry->getURI() ?>/" />
	<id><?php echo $entry->getURI() ?></id>
	<content type="xhtml" xml:lang="<?php echo L10n::$language ?>"><?php echo $entry->getProcessedContent() ?></content>
	<updated><?php echo $entry->getFormattedDate('modified', '%Y-%m-%dT%H:%i:%sZ') ?></updated>
	<published><?php echo $entry->getFormattedDate('created', '%Y-%m-%dT%H:%i:%sZ') ?></published>
	<generator uri="http://fuji.sourceforge.net" version="0.1">Fuji</generator>
	<author><name><?php echo htmlentities(User::getUser($entry->userref)->nickname, ENT_NOQUOTES) ?></name></author>
	<?php 	if(count($entry) > 0)
			foreach($entry->getChildren('created DESC', 10) as $post) { ?>
	<entry>
		<title><?php echo htmlentities($post->title, ENT_NOQUOTES) ?></title>
		<link href="<?php echo '../#' . $post->getURI() ?>/" />
		<id><?php echo $post->getURI() ?></id>
		<content type="xhtml" xml:lang="<?php echo L10n::$language ?>"><?php echo $post->getProcessedContent() ?></content>
		<author><name><?php echo htmlentities(User::getUser($post->userref)->nickname, ENT_NOQUOTES) ?></name></author>
		<updated><?php echo $post->getFormattedDate('modified', '%Y-%m-%dT%H:%i:%sZ') ?></updated>			
	</entry>
	<?php } ?>
</feed>