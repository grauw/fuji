<?xml version="1.0" encoding="UTF-8"?>
<fuji:item xmlns="http://www.w3.org/1999/xhtml"
	xmlns:fuji="http://fuji.sourceforge.net/ns"
	xml:lang="<?php echo $this->l10n->language ?>"
	loadcontext="threadsDiv">
	<fuji:content>
		<div class="box">
			<h2>Calendar day <?php echo strftime($this->l10n->date_format, $this->date) ?></h2>
			
			<div class="content">
			
			<div class="section">
				<h3><?php echo $this->l10n->events ?></h3>
				<p><?php echo $this->l10n->none ?></p>
			</div>
			
			<div class="section">
				<h3><?php echo $this->l10n->active_threads ?></h3>
				
				<?php if (count($this->entries)) { ?>
				<ul>
					<?php foreach ($this->entries as $entry) { ?>
					<li><a href="#<?php echo $entry->getURI() ?>"><?php echo $entry->title ?></a></li>
					<?php } ?>
				</ul>
				<?php } else { ?>
				<p><?php echo $this->l10n->none ?></p>
				<?php } ?>
				
			</div>
			
			</div>
		</div>
	</fuji:content>
</fuji:item>