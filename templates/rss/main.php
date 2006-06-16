<?php echo $template->getPrefix() ?>
<rss version="2.0">
	<channel>
		<title><?php echo $entry->title ?></title>
		<link><?php echo $template->getBaseURL() ?></link>
		<description><?php echo $entry->getContent() ?></description>
		<language><?php echo L10n::$language ?></language>
		<?php 	if(count($entry) > 0)
				foreach(Entry::getEntriesByType(4, 'created', 10) as $post) { ?>
		<item>
			<title><?php echo $post->title ?></title>
			<link><?php echo $template->getBaseURL() . '#item/' . $post->id ?></link>
			<description><?php echo $post->getContent() ?></description>			
		</item>
		<?php } ?>
	</channel>
</rss>