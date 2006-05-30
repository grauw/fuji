Fuji = {
	forum : 0,
	thread : 0,
	replyDialog : null,
	newThreadDialog : null,
	deleteThreadDialog : null,
	editThreadDialog : null,
	loadFora : function() {
		var callback =
		{
			success: Fuji.loadForumNode,
			failure: function(o) {alert('failure')},
			argument: []
		}
		YAHOO.util.Connect.asyncRequest('GET', 'item/1', callback, null); 
	},
	loadForumNode : function(oElm) {
		var oSource = oElm.responseXML;
		var oTarget = document.getElementById('foraDiv');
		var oForumTreeDiv = oSource.getElementById('forumTreeDiv');
		while(oForumTreeDiv.hasChildNodes())
		{
			var oElm = oForumTreeDiv.firstChild;
			if(oElm.tagName == 'script')
			{
				var oHead = document.getElementsByTagName('head')[0];
				var oScript = document.createElement('script');
				oScript.type = 'text/javascript';
				oScript.id = 'treeLoadScript';
				var oScriptText = document.createTextNode(oElm.firstChild.nodeValue);
				oScript.appendChild(oScriptText);
				oForumTreeDiv.removeChild(oElm);
				var oOldScript = document.getElementById('treeLoadScript');
				if(oOldScript) oHead.removeChild(oOldScript);
				oHead.appendChild(oScript);
			}
			else
				oTarget.appendChild(oElm);
		}
		if(Fuji.ForumTree)	Fuji.ForumTree.treeLoad();
	},
	loadThreads : function(i) {
		var callback =
		{
			success: Fuji.loadThreadNode,
			failure: function(o) {alert('failure')},
			argument: []
		}
		Fuji.forum = i;		
		YAHOO.util.Connect.asyncRequest('GET', 'item/' + i, callback, null); 
	},
	loadThreadNode : function(oElm) {
		Fuji.clearThreads();	
		var oSource = oElm.responseXML;
		var oTarget = document.getElementById('threadsTableDiv');
		var oThreadsTableDiv = oSource.getElementById('threadsTableDiv');
		while(oThreadsTableDiv.hasChildNodes())
		{
			var oElm = oThreadsTableDiv.firstChild;
			if(oElm.tagName == 'script')
			{
				var oHead = document.getElementsByTagName('head')[0];
				var oScript = document.createElement('script');
				oScript.type = 'text/javascript';
				var oScriptText = document.createTextNode(oElm.firstChild.nodeValue);
				oScript.appendChild(oScriptText);
				oThreadsTableDiv.removeChild(oElm);
				oHead.appendChild(oScript);
			}
			else
				oTarget.appendChild(oElm);
		}
		Fuji.resetThreadsActions(oThreadsTableDiv.getAttribute('may_post'), oThreadsTableDiv.getAttribute('may_edit'), oThreadsTableDiv.getAttribute('may_delete'));
		//rename title
		var oH1 = oSource.getElementsByTagName('body')[0].getElementsByTagName('h1')[0];
		var title = oH1.firstChild.nodeValue;
		var oTitle = document.getElementById('threadsTitle').firstChild;
		oTitle.nodeValue = oH1.getAttribute('default') + " - " + title;
	},
	loadPosts : function(i) {
		var callback =
		{
			success: Fuji.loadPostNode,
			failure: function(o) {alert('failure')},
			argument: []
		}
		Fuji.thread = i;
		YAHOO.util.Connect.asyncRequest('GET', 'item/' + i, callback, null); 
	},
	loadPostNode : function(oElm) {
		Fuji.clearPosts();
		document.getElementById('postsActions').style.display = 'block';		
		var oSource = oElm.responseXML;
		var oTarget = document.getElementById('postsListDiv');
		var oPostsListDiv = oSource.getElementById('postsListDiv');
		while(oPostsListDiv.hasChildNodes())
		{
			var oElm = oPostsListDiv.firstChild;
			if(oElm.tagName == 'script')
			{
				var oHead = document.getElementsByTagName('head')[0];
				var oScript = document.createElement('script');
				oScript.type = 'text/javascript';
				var oScriptText = document.createTextNode(oElm.firstChild.nodeValue);
				oScript.appendChild(oScriptText);
				oPostsListDiv.removeChild(oElm);
				oHead.appendChild(oScript);
			}
			else
				oTarget.appendChild(oElm);
		}
		Fuji.resetPostsActions(oPostsListDiv.getAttribute('may_post'), oPostsListDiv.getAttribute('may_edit'), oPostsListDiv.getAttribute('may_delete'));
		//rename title
		var oH1 = oSource.getElementsByTagName('body')[0].getElementsByTagName('h1')[0];
		var title = oH1.firstChild.nodeValue;
		document.getElementById('replyT').value = 'Re: ' + title;
		document.getElementById('replyContent').value = '';
		var oTitle = document.getElementById('postsTitle').firstChild;
		oTitle.nodeValue = oH1.getAttribute('default') + " - " + title;
		oTarget.setAttribute("threadid", i);		
	},
	/*******************************************************************/
	/*loadMenu : function() {
		var callback =
		{
			success: Fuji.loadMenuNode,
			failure: function(o) {alert('failure')},
			argument: []
		}
		YAHOO.util.Connect.asyncRequest('GET', 'item/' + 1, callback, null); 
	},
	loadMenuNode : function(oElm) {
		var oSource = oElm.responseXML;
		var oTarget = document.getElementById('menuDiv');
		var oForumTreeDiv = oSource.getElementById('forumTreeDiv');
		while(oForumTreeDiv.hasChildNodes())
		{
			var oElm = oForumTreeDiv.firstChild;
			if(oElm.tagName == 'script')
			{
				var oHead = document.getElementsByTagName('head')[0];
				var oScript = document.createElement('script');
				oScript.type = 'text/javascript';
				oScript.id = 'treeLoadScript';
				var oScriptText = document.createTextNode(oElm.firstChild.nodeValue);
				oScript.appendChild(oScriptText);
				oForumTreeDiv.removeChild(oElm);
				var oOldScript = document.getElementById('treeLoadScript');
				if(oOldScript) oHead.removeChild(oOldScript);
				oHead.appendChild(oScript);
			}
			else
				oTarget.appendChild(oElm);
		}
		if(Fuji.ForumTree)	Fuji.ForumTree.treeLoad();
	},*/
	/*************************************************************************/
	clearThreads : function() {
		var oThreadsTableDiv = document.getElementById('threadsTableDiv');
		while(oThreadsTableDiv.hasChildNodes())
			oThreadsTableDiv.removeChild(oThreadsTableDiv.firstChild);
	},
	clearPosts : function() {
		var oPostsListDiv = document.getElementById('postsListDiv');
		while(oPostsListDiv.hasChildNodes())
			oPostsListDiv.removeChild(oPostsListDiv.firstChild);
	},
	reloadThreads : function() {
		oTarget = document.getElementById('threadsTableDiv');
		if(Fuji.forum)
			Fuji.loadThreads(Fuji.forum);
	},
	reloadPosts : function() {
		oTarget = document.getElementById('postsListDiv');
		if(Fuji.thread)
			Fuji.loadPosts(Fuji.thread);
	},
	createReplyDialog : function() {
		var replyDialog = new YAHOO.widget.Dialog("replyDialog", { visible: false, fixedCenter: true});
		var handleCancel = function() {
			this.cancel();
		}
		var handleSubmit = function() {
			document.getElementById('replyRef').value = Fuji.thread;
			document.getElementById('replyUserref').value = Fuji.user;
			Fuji.reloadThreads();
			this.submit();
		}
		replyDialog.cfg.queueProperty("buttons", [ { text:"Submit", handler:handleSubmit, isDefault:true },
											   { text:"Cancel", handler:handleCancel } ]);
		replyDialog.cfg.queueProperty("onsuccess", Fuji.reloadPosts);
		replyDialog.render();
		Fuji.replyDialog = replyDialog;
	},
	showReplyDialog : function() {
		Fuji.replyDialog.show();
	},
	createNewThreadDialog : function() {
		var newThreadDialog = new YAHOO.widget.Dialog("newThreadDialog", { visible: false, fixedCenter: true });
		var handleCancel = function() {
			this.cancel();
		}
		var handleSubmit = function() {
			document.getElementById('newThreadRef').value = Fuji.forum;
			document.getElementById('newThreadUserref').value = Fuji.user;
			this.submit();
		}
		newThreadDialog.cfg.queueProperty("buttons", [ { text:"Submit", handler:handleSubmit, isDefault:true },
											   { text:"Cancel", handler:handleCancel } ]);
		newThreadDialog.cfg.queueProperty("onsuccess", Fuji.reloadThreads);
		newThreadDialog.render();
		Fuji.newThreadDialog = newThreadDialog;
	},
	showNewThreadDialog : function() {
		Fuji.newThreadDialog.show();
	},	
	resetThreadsActions : function(p, e, r) {
		if(p == '1')
			document.getElementById('actionNewThread').style.display = 'inline';
		else
			document.getElementById('actionNewThread').style.display = 'none';
		if(e == '1')
			document.getElementById('actionEditThread').style.display = 'inline';
		else
			document.getElementById('actionEditThread').style.display = 'none';
		if(r == '1')
			document.getElementById('actionDeleteThread').style.display = 'inline';			
		else
			document.getElementById('actionDeleteThread').style.display = 'none';					
	},
	resetPostsActions : function(p, e, r) {
		if(p == '1')
			document.getElementById('actionReplyPost').style.display = 'inline';
		else
			document.getElementById('actionReplyPost').style.display = 'none';
		if(e == '1')
			document.getElementById('actionEditPost').style.display = 'inline';
		else
			document.getElementById('actionEditPost').style.display = 'none';
		if(r == '1')
			document.getElementById('actionDeletePost').style.display = 'inline';			
		else
			document.getElementById('actionDeletePost').style.display = 'none';					
	},
	createDeleteThreadDialog : function() {
		var deleteThreadDialog = new YAHOO.widget.Dialog("deleteThreadDialog", { visible: false, fixedCenter: true, width: '200px' });
		var handleCancel = function() {
			this.cancel();
		}
		var handleSubmit = function() {
			var oDeleteThreadSelect = document.getElementById('deleteThreadSelect');
			var selected = Fuji.util.getSelectedItems(oDeleteThreadSelect);
			document.getElementById('deleteThreadId').value = selected;
			document.getElementById('deleteThreadUserid').value = Fuji.user;
			Fuji.deleteThread();
			deleteThreadDialog.hide();
		}
		deleteThreadDialog.cfg.queueProperty("buttons", [ { text:"Delete", handler:handleSubmit, isDefault:true },
											   { text:"Cancel", handler:handleCancel} ]);
		deleteThreadDialog.render();
		Fuji.deleteThreadDialog = deleteThreadDialog;
	},
	showDeleteThreadDialog : function() {
		var oDeleteThreadSelect = document.getElementById('deleteThreadSelect');
		while(oDeleteThreadSelect.hasChildNodes())
			oDeleteThreadSelect.removeChild(oDeleteThreadSelect.firstChild);
		var oThreadsTable = document.getElementById('threadsTable');
		var aThreadsTableRows = oThreadsTable.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
		for(var n = 0; n < aThreadsTableRows.length; n++)
		{
			var oElm = document.createElement('option');
			var sEntryId = aThreadsTableRows[n].getAttribute('entryid');
			var sEntryTitle = aThreadsTableRows[n].getAttribute('entrytitle');
			oElm.value = sEntryId;
			oElm.appendChild(document.createTextNode(sEntryTitle));
			oDeleteThreadSelect.appendChild(oElm);
		}
		Fuji.deleteThreadDialog.show();
	},
	deleteThread : function() {
		
		var id = document.getElementById('deleteThreadId').value;
		var callback =
		{
			success: Fuji.reloadThreads,
			failure: function(o) {alert('failure')},
			argument: []
		}
		YAHOO.util.Connect.asyncRequest('DELETE', 'item/' + id, callback, null); 
	},
	createEditThreadDialog : function() {
		var editThreadDialog = new YAHOO.widget.Dialog("editThreadDialog", { visible: false, fixedCenter: true, width: '200px' });
		var handleCancel = function() {
			this.cancel();
		}
		var handleSubmit = function() {
			var oEditThreadSelect = document.getElementById('editThreadSelect');
			var selected = Fuji.util.getSelectedItems(oEditThreadSelect);
			document.getElementById('editThreadEntryid').value = selected;
			document.getElementById('editThreadUserid').value = Fuji.user;
			Fuji.editThread();
			Fuji.editThreadDialog.hide();
			Fuji.reloadThreads();
		}
		editThreadDialog.cfg.queueProperty("buttons", [ { text:"OK", handler:handleSubmit, isDefault:true },
											   { text:"Cancel", handler:handleCancel} ]);
		editThreadDialog.cfg.queueProperty("onsuccess", Fuji.reloadThreads);
		editThreadDialog.render();
		Fuji.editThreadDialog = editThreadDialog;
	},
	showEditThreadDialog : function() {
		var oEditThreadSelect = document.getElementById('editThreadSelect');
		while(oEditThreadSelect.hasChildNodes())
			oEditThreadSelect.removeChild(oEditThreadSelect.firstChild);
		var oThreadsTable = document.getElementById('threadsTable');
		var aThreadsTableRows = oThreadsTable.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
		for(var n = 0; n < aThreadsTableRows.length; n++)
		{
			var oElm = document.createElement('option');
			var sEntryId = aThreadsTableRows[n].getAttribute('entryid');
			var sEntryTitle = aThreadsTableRows[n].getAttribute('entrytitle');
			oElm.value = sEntryId;
			oElm.appendChild(document.createTextNode(sEntryTitle));
			oEditThreadSelect.appendChild(oElm);
		}
		Fuji.editThreadDialog.show();
	},
	editThread : function() {
		var id = document.getElementById('editThreadEntryid').value;
		alert(id);
		var callback =
		{
			success: function(o) {alert('success')},
			failure: function(o) {alert('failure')},
			argument: []
		}
		YAHOO.util.Connect.setForm('editThreadF');
		alert(YAHOO.util.Connect._sFormData);
		YAHOO.util.Connect.asyncRequest('POST', 'item/' + id, callback, null); 
	},
	editThreadItemSelected : function() {
		var oEditThreadSelect = document.getElementById('editThreadSelect');
		var selected = Fuji.util.getSelectedItems(oEditThreadSelect);
	},
	init : function() {
		Fuji.loadFora();
		Fuji.loadThreads(2);
		/*Fuji.loadMenu();*/
		Fuji.createReplyDialog();
		Fuji.createNewThreadDialog();
		Fuji.createDeleteThreadDialog();
		Fuji.createEditThreadDialog();		
	}
}
Fuji.util = {
	getSelectedItems : function(oSelect) {
		var aSelectItems = oSelect.getElementsByTagName('option');
		var selected = "";
		for(var n = 0; n < aSelectItems.length; n++)
			if(aSelectItems[n].selected)
				if(selected == "")
					selected += aSelectItems[n].value;
				else
					selected += "," + aSelectItems[n].value;
		return selected;
	}
}