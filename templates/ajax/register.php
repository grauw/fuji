<?php
	$l10ns = L10nManager::getL10ns();
	$styles = Stylesheet::getStylesheets(false);
?>
<?php echo $this->getPrefix() ?>
<fuji:item xmlns="http://www.w3.org/1999/xhtml"
	xmlns:fuji="http://fuji.sourceforge.net/ns"
	xml:lang="<?php echo $this->l10n->language ?>"
	loadcontext="threadsDiv">
	<fuji:content>
		<div class="box">
			<h2 ><?php echo $this->l10n->register ?></h2>
			<div class="content">
				<form action="register" method="POST">
					<p>xxx On this page you can set up your account.</p>
					
					<!-- TODO: put style in CSS -->
					<table style="width: auto;">
						<tr><td><?php echo $this->l10n->nickname ?></td><td><input name='nickname' type='text' /></td></tr>
						<tr><td><?php echo $this->l10n->email ?></td><td><input name='email' type='text' /></td></tr>
						<tr><td><?php echo $this->l10n->password ?></td><td><input name='password1' type='password' /></td></tr>
						<tr><td><?php echo $this->l10n->reenter_password ?></td><td><input name='password2' type='password' /></td></tr>
					</table>
					
					<button type="button" onclick="Fuji.submitItem(this.form)"><?php echo $this->l10n->submit ?></button>
				</form>
			</div>
		</div>
	</fuji:content>
</fuji:item>