<?php echo $this->getPrefix() ?>
<fuji:item xmlns="http://www.w3.org/1999/xhtml"
	xmlns:fuji="http://fuji.sourceforge.net/ns"
	xml:lang="<?php echo $this->l10n->language ?>"
	loadcontext="threadsDiv">
	<fuji:content>
		<div class="box">
			<h2><?php echo $this->l10n->register ?></h2>
			<div class="content">
				<p><?php echo $this->message ?></p>
			</div>
		</div>
	</fuji:content>
</fuji:item>