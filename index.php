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
	<div class="logoDiv logo window">Fuji</div>
	<div id="foraDiv" class="window">
		<div id="foraTitle" class="windowTitle"><?php echo L10n::$fora ?></div>
	</div>
	<div id="menuDiv" class="window">
		<div class="windowTitle"><?php echo L10n::$menu ?></div>
	<?php if(UserManager::getUser()->nickname == 'anonymous') echo '<p><a href="">Log in</a></p>'; else echo '<p>Logged in as ' . UserManager::getUser()->nickname . '</p>'; ?>
	<?php if(UserManager::getUser()->nickname == 'anonymous') echo '<p><a href="scripts/register/">Register</a></p>'; ?>
	</div>
	<div id="threadsDiv" class="window">
		<div id="threadsTitle" class="windowTitle"><?php echo L10n::$threads ?></div>
		<div id="threadPath" class="windowInfo"></div> 
		<div id="threadsActions" class="windowActions">
			<span id="actionNewThread"><a href="javascript:Fuji.showNewThreadDialog()"><?php echo L10n::$new ?></a></span>
			<span id="actionEditThread"><a href="javascript:Fuji.showEditThreadDialog()"><?php echo L10n::$edit ?></a></span>
			<span id="actionDeleteThread"><a href="javascript:Fuji.showDeleteThreadDialog()"><?php echo L10n::$delete ?></a></span>
			<span><?php echo L10n::$search ?></span>
			<span><a href="javascript:Fuji.reloadThreads()"><?php echo L10n::$refresh ?></a></span>
		</div>
		<div id="threadsTableDiv"></div>
	</div>
	<div id="postsDiv" class="window">
		<div id="postsTitle" class="windowTitle"><?php echo L10n::$posts ?></div>
		<div id="postsActions" class="windowActions">
			<span id="actionReplyPost"><a href="javascript:Fuji.showReplyDialog()"><?php echo L10n::$reply ?></a></span>
			<span id="actionEditPost"><?php echo L10n::$edit ?></span>
			<span id="actionDeletePost"><?php echo L10n::$delete ?></span>
			<span><?php echo L10n::$search ?></span>
			<span><a href="javascript:Fuji.reloadPosts()"><?php echo L10n::$refresh ?></a></span>
		</div>
		<div id="postsListDiv"></div>
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