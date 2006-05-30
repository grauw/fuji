<?php
	require_once("scripts/usermanager.php");
	UserManager::authenticate();
	
	if(isset($lang))
		require_once("l10n/$lang.php");
	else
		require_once("l10n/default.php");
?>
<!DOCTYPE html 
     PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
     "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="<?php echo L10n::$language ?>" xml:lang="<?php echo L10n::$language ?>">
<head>
	<title>Fuji</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link rel="stylesheet" type="text/css" href="css/fuji.css" />
	<link rel="stylesheet" type="text/css" href="css/dark.css" />
	<link rel="stylesheet" type="text/css" href="css/treeview.css" />
	<link rel="stylesheet" type="text/css" href="css/dark-container.css" />	    
	<script type="text/javascript" src="js/yui/yahoo/yahoo.js"></script>
	<script type="text/javascript" src="js/yui/dom/dom.js"></script>
	<script type="text/javascript" src="js/yui/event/event.js"></script>
	<script type="text/javascript" src="js/yui/treeview/treeview.js"></script>
	<script type="text/javascript" src="js/yui/connection/connection.js"></script>
	<script type="text/javascript" src="js/yui/container/container.js"></script>
	<script type="text/javascript" src="js/yui/animation/animation.js"></script>
	<script type="text/javascript" src="js/yui/dragdrop/dragdrop.js"></script>
	<script type="text/javascript" src="js/fuji.js"></script>
	<script type="text/javascript"><?php echo "Fuji.user = " . UserManager::getUser()->id .";\n"?></script>
</head>
<body onload="Fuji.init()">
	<h1 class="logoDiv logo window">Fuji</h1>
	<div id="foraDiv" class="window">
	</div>
	<div id="menuDiv" class="window">
		<h2 class="windowTitle"><?php echo L10n::$menu ?></h2>
		<div id="menuListDiv"></div>
	</div>
	<div id="threadsDiv" class="window">
	</div>
	<div id="postsDiv" class="window">
	</div>
	<div id="newThreadDialog">
		<div id="newThreadTitle" class="hd"><?php echo L10n::$new_thread ?></div>
		<div id="newThreadForm" class="bd">
			<form id="newThreadF" name="newThreadF" method="POST" action="item/">
				<input id='newThreadRef' name='ref' type='hidden' value='' />
				<input id='newThreadUserref' name='userref' type='hidden' value='' />
				<input id='newThreadType' name='type' type='hidden' value='4' />
				<?php echo L10n::$title ?>: <input id='newThreadT' name='title' type='text' value='' /><br />
				<?php echo L10n::$message ?>: <textarea id='newThreadContent' name='content' type='text' value=''></textarea>
			</form>
		</div>
	</div>
	<div id="deleteThreadDialog">
		<div id="deleteThreadTitle" class="hd"><?php echo L10n::$delete_thread ?></div>
		<div id="deleteThreadForm" class="bd">
			<form id="deleteThreadF" name="deleteThreadF" method="POST">
				<input id='deleteThreadId' name='threadid' type='hidden' value='' />
				<input id='deleteThreadUserid' name='userid' type='hidden' value='' />
				<select id='deleteThreadSelect' name='select' multiple size='10'>
				</select>
			</form>
		</div>		
	</div>
	<div id="editThreadDialog">
		<div id="editThreadTitle" class="hd"><?php echo L10n::$edit_thread ?></div>
		<div id="editThreadForm" class="bd">
			<form id="editThreadF" name="editThreadF" method="POST" enctype="text/plain">
				<input id='editThreadEntryid' name='entryid' type='hidden' value='' />
				<input id='editThreadUserid' name='userid' type='hidden' value='' />
				<?php echo L10n::$title ?>: <input id='editThreadT' name='title' type='text' value='' /><br />
				<?php echo L10n::$message ?>: <textarea id='editThreadContent' name='content' type='text' value=''></textarea>
			</form>
		</div>
	</div>	
	<div id="replyDialog">
		<div id="replyTitle" class="hd"><?php echo L10n::$reply ?></div>
		<div id="replyForm" class="bd">
			<form id="replyF" name="replyF" method="POST" action="item/">
				<input id='replyRef' name='ref' type='hidden' value='' />
				<input id='replyUserref' name='userref' type='hidden' value='' />
				<input id='replyType' name='type' type='hidden' value='4' />
				<?php echo L10n::$title ?>: <input id='replyT' name='title' type='text' value='' /><br />
				<?php echo L10n::$message ?>: <textarea id='replyContent' name='content' type='text' value=''></textarea>
			</form>
		</div>
	</div>
</body>
</html>
