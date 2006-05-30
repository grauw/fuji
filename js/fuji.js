Fuji = {
	NS : 'http://fuji.sourceforge.net/ns',
	
	forum : 0,
	thread : 0,
	replyDialog : null,
	newThreadDialog : null,
	deleteThreadDialog : null,
	editThreadDialog : null,
	loadItem : function(uri) {
		if (document.getElementById(uri)) {
			Fuji.selectDeckItem(document.getElementById(uri));
		} else {
			var callback =
			{
				success: Fuji.loadItemNode,
				failure: function(o) {alert('failure: ',o.argument.uri)},
				argument: { uri: uri }
			}
			YAHOO.util.Connect.asyncRequest('GET', uri, callback, null); 
		}
	},
	reloadItem : function(uri) {
		if (document.getElementById(uri)) {
			var oElm = document.getElementById(uri);
			oElm.parentNode.removeChild(oElm);
		}
		loadItem(uri);
	},
	loadItemNode : function(oXMLHttpObject) {
		var oSource = oXMLHttpObject.responseXML;
		var oTargetId = oSource.documentElement.getAttribute('loadcontext');
		var oTarget = document.getElementById(oTargetId);
		var oContent = oSource.getElementsByTagNameNS(Fuji.NS,'content')[0];
		var oTargetDiv = document.createElement('div');
		oTargetDiv.id = oXMLHttpObject.argument.uri;
		oTarget.appendChild(oTargetDiv);
		for (var i=0; i < oContent.childNodes.length; i++) {
			oTargetDiv.appendChild(document.importNode(oContent.childNodes.item(i), true));
		}
		if (oSource.documentElement.hasAttribute('parent')) {
			oTargetDiv.setAttribute('parent',oSource.documentElement.getAttribute('parent'));
		}
		Fuji.selectDeckItem(oTargetDiv);
		var oLoadHandler = oSource.getElementsByTagNameNS(Fuji.NS,'loadhandler');
		if (oLoadHandler.length) {
			eval(oLoadHandler[0].firstChild.nodeValue)
		}
	},
	selectDeckItem : function(oElm) {
		for (var i=0; i < oElm.parentNode.childNodes.length; i++) {
			var oDeckItem = oElm.parentNode.childNodes.item(i);
			if (oDeckItem.nodeType == 1) {
				oElm.parentNode.childNodes.item(i).style.display = 'none';
			}
		}
		oElm.style.display = '';
		if (oElm.hasAttribute('parent')) {
			Fuji.loadItem(oElm.getAttribute('parent'));
		}
	},
	loadMenu : function() {
		var callback =
		{
			success: Fuji.loadMenuNode,
			failure: function(o) {alert('failure')},
			argument: []
		}
		YAHOO.util.Connect.asyncRequest('GET', 'menu/', callback, null); 
	},
	loadMenuNode : function(oElm) {
		Fuji.clearMenu();
		var oSource = oElm.responseXML;
		var oTarget = document.getElementById('menuListDiv');
		var oMenu = oSource.getElementById('menuItems');
		oTarget.appendChild(oMenu);
	},
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
	clearMenu : function() {
		var oMenuListDiv = document.getElementById('menuListDiv');
		while(oMenuListDiv.hasChildNodes())
			oMenuListDiv.removeChild(oMenuListDiv.firstChild);
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
			document.getElementById('editThreadEntryid').value = Fuji.forum;
			document.getElementById('editThreadUserid').value = Fuji.user;
			Fuji.editThread();
			Fuji.editThreadDialog.hide();
		}
		editThreadDialog.cfg.queueProperty("buttons", [ { text:"OK", handler:handleSubmit, isDefault:true },
											   { text:"Cancel", handler:handleCancel} ]);
		editThreadDialog.cfg.queueProperty("onsuccess", Fuji.reloadThreads);
		editThreadDialog.render();
		Fuji.editThreadDialog = editThreadDialog;
	},
	showEditThreadDialog : function() {
		var callback =
		{
			success: Fuji.updateEditDialog,
			failure: function(o) {alert('failure')},
			argument: []
		}
		YAHOO.util.Connect.asyncRequest('GET', 'item/' + Fuji.forum + '/source', callback, null); 
		Fuji.editThreadDialog.show();
	},
	updateEditDialog : function(oElm) {
		var oSource = oElm.responseXML;
		var oEditThreadTitle = document.getElementById('editThreadT');
		var oEditThreadContent = document.getElementById('editThreadContent');
		oEditThreadTitle.value = oSource.getElementById('title').innerHTML;
		oEditThreadContent.value = oSource.getElementById('content').innerHTML;			
	},
	editThread : function() {
		var callback =
		{
			success: function(o) {Fuji.reloadThreads()},
			failure: function(o) {alert('failure')},
			argument: []
		}
		YAHOO.util.Connect.setForm('editThreadF');
		YAHOO.util.Connect.asyncRequest('POST', 'item/' + Fuji.thread, callback, null); 
	},
	startUrlChangeListener : function() {
		setTimeout(Fuji.urlChangeListener, 300);
	},
	urlChangeListener : function() {
		if (location.hash != Fuji.currentURLHash) Fuji.urlChangeCallback(location.hash);
		Fuji.currentURLHash = location.hash;
		Fuji.startUrlChangeListener();
	},
	urlChangeCallback : function(hash) {
		var uri = hash.indexOf('#') != -1 ? hash.substr(hash.indexOf('#')+1) : hash;
		Fuji.loadItem(uri);
	},
	init : function() {
		Fuji.loadMenu();
		Fuji.createReplyDialog();
		Fuji.createNewThreadDialog();
		Fuji.createDeleteThreadDialog();
		Fuji.createEditThreadDialog();
		Fuji.loadItem('item/1');
		if (location.hash == '') location.hash='#item/2';
		Fuji.startUrlChangeListener();
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