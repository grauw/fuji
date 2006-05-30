<?php
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
	<head>
		<title>Simple poster</title>
	</head>
	<body>
		<form id="replyForm" name="replyForm" action="" method="post" onsubmit="javascript:Fuji.submitReply();">
			<input name='ref' type='hidden' value='' />
			<input name='userref' type='hidden' value='' />
			<input name='type' type='hidden' value='' />
			<p>Title: <input name='title' type='text' value='Titel' /></p>
			<p>Text: <textarea name='content' type='text' value='De content'></textarea></p>
			    <!--<option value="1">ROOT/SYSTEM/(input other name)</option>
			        <option value="2">FORUMCONTAINER (forum container (directory for forums))</option>
			        <option value="3">FORUM (list with descriptions)</option>
			        <option value="4">POST (single post/single reply)</option>
			        <option value="5">POLL</option>
			        <option value="6">POLL_ITEM/option> -->
			<p><input name="submit" type="submit" value="Submit post" /></p>
		</form>
		<a href="javascript:Fuji.submitReply();">click</a>
	</body>
</html>
<!---
		<form id="messageForm" action="" name="messageForm" method="post" onsubmit="submitMessage(); return false;">
			<input type="text" size="55" name="message" id="message" class="inputtext" autocomplete="off" />
			<input type="button" value="verzenden" name="sendmessage" id="sendmessage" />
		</form>	
-->
