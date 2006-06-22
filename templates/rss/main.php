<?php echo $template->getPrefix() ?>
<rss version="2.0">
	<channel>
		<title><?php echo htmlentities($entry->title, ENT_NOQUOTES) ?></title>
		<link>..</link>
		<description><?php echo $entry->getProcessedContent() ?></description>
		<pubDate><?php echo $entry->getFormattedDate('created', '%a, %d %b %Y %H:%i:%s GMT') ?></pubDate>
		<language><?php echo L10n::$language ?></language>
		<?php 	if(count($entry) > 0)
				foreach(Entry::getEntriesByType(4, 'created DESC', 10) as $post) { ?>
		<item>
			<title><?php echo htmlentities($post->title, ENT_NOQUOTES) ?></title>
			<link><?php echo '../#item/' . $post->id ?>/</link>
			<description><?php echo $post->getProcessedContent() ?></description>
			<pubDate><?php echo $post->getFormattedDate('created', '%a, %d %b %Y %H:%i:%s GMT') ?></pubDate>			
		</item>
		<?php } ?>
	</channel>
</rss>