<?php echo $template->getPrefix() ?>
<rss version="2.0">
	<channel>
		<title><?php echo $entry->title ?></title>
		<link><?php echo /*$template->getBaseURL() . */'../#item/' . $entry->id ?>/</link>
		<pubDate><?php echo $entry->getFormattedDate('created', '%a, %d %b %Y %H:%i:%s GMT') ?></pubDate>
		<description><?php echo $entry->getContent() ?></description>
		<language><?php echo L10n::$language ?></language>		
		<?php 	if(count($entry) > 0)
				foreach($entry->getChildren('created DESC', 10) as $post) { ?>
		<item>
			<title><?php echo $post->title ?></title>
			<link><?php echo /*$template->getBaseURL() . */'../#item/' . $post->id ?>/</link>
			<description><?php echo $post->getContent() ?></description>
			<pubDate><?php echo $post->getFormattedDate('created', '%a, %d %b %Y %H:%i:%s GMT') ?></pubDate>
		</item>
		<?php } ?>
	</channel>
</rss>