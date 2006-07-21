<?php $this->getPrefix() ?>
<?xml version="1.0" encoding="UTF-8"?>
<feed xmlns="http://www.w3.org/2005/Atom">
	<title><?php echo $this->entry->title ?></title>
	<link href="../" />
	<id><?php echo $this->entry->getURI() ?></id>
	<content type="xhtml" xml:lang="<?php echo L10n::$language ?>"><?php echo $this->entry->getProcessedContent() ?></content>
	<updated><?php echo $this->entry->getFormattedDate('modified', '%Y-%m-%dT%H:%i:%sZ') ?></updated>
	<published><?php echo $this->entry->getFormattedDate('created', '%Y-%m-%dT%H:%i:%sZ') ?></published>
	<generator uri="http://fuji.sourceforge.net" version="0.1">Fuji</generator>
	<author><name><?php echo htmlentities(User::getUser($this->entry->userref)->nickname, ENT_NOQUTOES) ?></name></author>
	<?php 	if(count($this->entry) > 0)
			foreach(Entry::getEntriesByType(4, 'created DESC', 10) as $post) { ?>
	<entry>
		<title><?php echo htmlentities($post->title, ENT_NOQUOTES) ?></title>
		<link href="<?php echo '../#' . $post->getURI() ?>/" />
		<id><?php echo $post->getURI() ?></id>
		<content type="xhtml" xml:lang="<?php echo L10n::$language ?>"><?php echo $post->getProcessedContent() ?></content>
		<author><name><?php echo htmlentities(User::getUser($post->userref)->nickname, ENT_NOQUOTES) ?></name></author>
		<updated><?php echo $post->getFormattedDate('modified', '%Y-%m-%dT%H:%i:%sZ') ?></updated>
		<published><?php echo $post->getFormattedDate('created', '%Y-%m-%dT%H:%i:%sZ') ?></published>			
	</entry>
	<?php } ?>
</feed>