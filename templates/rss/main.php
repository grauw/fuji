<?php echo $this->getPrefix() ?>
<rss version="2.0">
	<channel>
		<title><?php echo htmlentities($entry->title, ENT_NOQUOTES) ?></title>
		<link>..</link>
		<description><?php echo $entry->getProcessedContent() ?></description>
		<generator>Fuji 0.1</generator>
		<docs>http://blogs.law.harvard.edu/tech/rss</docs>
		<pubDate><?php echo $entry->getFormattedDate('created', '%a, %d %b %Y %H:%i:%s GMT') ?></pubDate>
		<lastBuildDate><?php echo $entry->getFormattedDate('modified', '%a, %d %b %Y %H:%i:%s GMT') ?></lastBuildDate>
		<language><?php echo L10n::$language ?></language>
		<?php 	if(count($entry) > 0)
				foreach(Entry::getEntriesByType(4, 'created DESC', 10) as $post) { ?>
		<item>
			<title><?php echo htmlentities($post->title, ENT_NOQUOTES) ?></title>
			<link><?php echo '../#' . $post->getURI() ?>/</link>
			<description><?php echo $post->getProcessedContent() ?></description>
			<pubDate><?php echo $post->getFormattedDate('created', '%a, %d %b %Y %H:%i:%s GMT') ?></pubDate>
			<lastBuildDate><?php echo $post->getFormattedDate('modified', '%a, %d %b %Y %H:%i:%s GMT') ?></lastBuildDate>			
		</item>
		<?php } ?>
	</channel>
</rss>