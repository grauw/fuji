<?php 
	header('refresh:5;URL='.$this->redirecturl);
	echo $this->getPrefix();
?>
<!DOCTYPE html
	PUBLIC "-//W3C//DTD XHTML 1.1 plus MathML 2.0//EN"
	"http://www.w3.org/Math/DTD/mathml2/xhtml-math11-f.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->l10n->language ?>">
<head>
	<title><?php echo $this->l10n->post ?></title>
	<?php echo $this->getHead() ?>
</head>
<body>
	<h1 class="logo"><a href="<?php echo $this->l10n->forum_link ?>"><?php echo $this->l10n->forum_name ?></a></h1>
	<div id="menuDiv" class="menuContainer box">
	<h2><?php echo $this->l10n->menu ?></h2>
	<?php if(UserManager::getUser()->nickname == 'anonymous') { ?>
		<p><?php echo $this->l10n->anonymous_user ?></p>
	<?php } else { ?>
		<p><?php echo $this->l10n->logged_in_as . ' ' . UserManager::getUser()->nickname ?></p>
	<?php } ?>
	</div>
	<div class="box">
	<h2><?php echo $this->l10n->post ?></h2>
	
	<div class="content">
	
	<p><?php printf($this->l10n->item_posted_redirect, '<a href="'.$this->redirecturl.'">', '</a>'); ?></p>
	
	</div>
	</div>
</body>
</html>