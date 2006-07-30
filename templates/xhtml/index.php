<?php
if(file_exists("../scripts/configuration/installer_disabled"))
{
	require_once("../scripts/usermanager.php");	
	UserManager::authenticate();
	echo $this->getPrefix();
?>
<!DOCTYPE html 
     PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
     "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="<?php echo L10n::$language ?>" xml:lang="<?php echo L10n::$language ?>">
<head>
	<title>Fuji</title>
	<?php echo $this->getHead() ?>
	<link id="rsslink" rel="alternate" type="application/rss+xml" href="item.rss" />
	<link id="atomlink" rel="alternate" type="application/atom+xml" href="item.atom" />
	<script type="text/javascript" src="js/yui/yahoo/yahoo-min.js"></script>
	<script type="text/javascript" src="js/yui/dom/dom-min.js"></script>
	<script type="text/javascript" src="js/yui/event/event-min.js"></script>
	<script type="text/javascript" src="js/yui/connection/connection-min.js"></script>
	<script type="text/javascript" src="js/yui/container/container-min.js"></script>
	<script type="text/javascript" src="js/yui/animation/animation-min.js"></script>
	<script type="text/javascript" src="js/yui/dragdrop/dragdrop-min.js"></script>
	<script type="text/javascript" src="js/fuji.js"></script>
	<script type="text/javascript" src="js/l10n.js.php"></script>
	<script type="text/javascript" src="js/dialogs.js"></script>
	<script type="text/javascript">
		Fuji.settings = { visual_effects : <?php echo UserManager::getUser()->visual_effects == '1' ? 'true' : 'false' ?> }
	</script>
</head>
<body onload="Fuji.boot()" id="fuji-body">
	<h1><a href="http://fuji.sourceforge.net">Fuji</a></h1>
	
	<h2>HTML version</h2>
	
	<p>Your browser is not compatible with the AJAX version of Fuji. The HTML version has limited functionality.</p>
	
	<p><a href="item/1">Browse the forum</a></p>
	
</body>
</html>
<?php } else { ?>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en:GB" xml:lang="en:GB">
<head>
	<title>Fuji</title>
	<?php echo $this->getHead() ?>
</head>
<body>
	<p>Your Fuji Forum has to be configured before you can start using it.</p>
	<p>Please run the <a href="install">installer</a> first.</p>
</body>
</html>
<?php } ?>
