<?php echo $this->getPrefix() ?>
<rss version="2.0">
	<channel>
		<title><?php echo htmlentities($this->entry->title, ENT_NOQUOTES) ?></title>
		<link><?php echo HttpRequest::$request->getAbsoluteContextPath() . '/' ?></link>
		<description><?php echo $this->entry->getProcessedContent() ?></description>
		<generator>Fuji 0.1</generator>
		<docs>http://blogs.law.harvard.edu/tech/rss</docs>
		<pubDate><?php echo $this->entry->getFormattedDate('created', '%a, %d %b %Y %H:%i:%s GMT') ?></pubDate>
		<lastBuildDate><?php echo $this->entry->getFormattedDate('modified', '%a, %d %b %Y %H:%i:%s GMT') ?></lastBuildDate>
		<language><?php echo $this->l10n->language ?></language>
		<?php 	if(count($this->entry) > 0)
				foreach(Entry::getEntriesByType(4, 'created DESC', 10) as $post) { ?>
		<item>
			<title><?php echo htmlentities($post->title, ENT_NOQUOTES) ?></title>
			<link><?php echo HttpRequest::$request->getAbsoluteContextPath() . '/#' . $post->getURI() ?></link>
			<description><?php echo $post->getProcessedContent() ?></description>
			<pubDate><?php echo $post->getFormattedDate('created', '%a, %d %b %Y %H:%i:%s GMT') ?></pubDate>
			<lastBuildDate><?php echo $post->getFormattedDate('modified', '%a, %d %b %Y %H:%i:%s GMT') ?></lastBuildDate>			
		</item>
		<?php } ?>
	</channel>
</rss>