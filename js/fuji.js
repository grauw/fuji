Fuji = {
	NS : 'http://fuji.sourceforge.net/ns',
	//TODO: these (simple)dialogs still need to be their creating function to be merged, somewhere under Fuji.Dialogs
	deleteDialog : null,			//pointer to the delete dialog
	deleteGroupDialog : null,		//pointer to the delete group dialog
	errorDialog : null,				//pointer to the error dialog	
	loadingDialog : null,			//pointer to the loading dialog	
	
	/**
	 * Loads the item with the specified URI using XMLHttpRequest
	 * @param {String} uri The URI to the item
	 */		
	loadItem : function(uri) {
		if (document.getElementById(uri)) {
			Fuji.selectDeckItem(document.getElementById(uri));
		} else {
			Fuji.reloadItem(uri);
		}
		document.getElementById('rsslink').href = 'rss/' + uri;
		document.getElementById('atomlink').href = 'atom/' + uri;
	},
	
	/**
	 * Reloads the item with the specified URI, and go up the tree depth nodes
	 * @param {String} uri The URI to the item
	 * @param {Int} depth Number of parents to reload recursively (optional, default: 0 = none)
	 */	
	reloadItem : function(uri, depth) {
		Fuji.Dialogs.newLoadingDialog();
		if (depth == undefined) depth = 0;
		for (var i=0; i<=depth; i++) {
			Fuji.reloadItemWithChild(uri);
			if (document.getElementById(uri)) {
				if (document.getElementById(uri).hasAttribute('parent')) {
					uri = document.getElementById(uri).getAttribute('parent');
				} else return;
			} else return;
		}
	},
	
	/**
	 * Reloads the item with the specified URI, and when loaded optionally also processes another document
	 * @param {String} uri The URI to the item
	 * @param {XMLDocument} oDocument the document to process additionally (optional)
	 * @param {XMLDocument} sURI the URI of the document to process additionally (optional)
	 */		
	reloadItemWithChild : function(uri, oDocument, sURI) {
		Fuji.Dialogs.newLoadingDialog();
		var callback =
		{
			success: Fuji.loadSuccess,
			failure: Fuji.loadFailure,
			argument: { uri: uri, oDocument: oDocument, sURI: sURI }
		}
		YAHOO.util.Connect.initHeader('Accept', 'application/x-fuji+xhtml+xml');
		YAHOO.util.Connect.asyncRequest('GET', uri, callback, null);
	},
	
	loadFailure : function(oXMLHttpObject) {
		Fuji.loadingDialog.hide();
		Fuji.Dialogs.newErrorDialog(oXMLHttpObject);
	},
	
	/**
	 * Handles the response of the loadItem/reloadItem request
	 * @param {Object} oXMLHttpObject The response object
	 */		
	loadSuccess : function(oXMLHttpObject) {
		if(!oXMLHttpObject.responseXML) return; //To prevent loops when you have insufficient rights
		var oDocument = oXMLHttpObject.responseXML;
		if(!oDocument.documentElement) //Internet Explorer-hack
		{
			oDocument = new ActiveXObject("Microsoft.XMLDOM");
			oDocument.loadXML(oXMLHttpObject.responseText);
		}
		Fuji.loadDocument(oDocument, oXMLHttpObject.argument.uri);
		if (oXMLHttpObject.argument.oDocument) {
			Fuji.loadDocument(oXMLHttpObject.argument.oDocument, oXMLHttpObject.argument.sURI);
		}
		Fuji.loadingDialog.hide();
	},
	
	/**
	 * Processes from a fuji:item document into a forum page
	 * @param {XMLDocument} oDocument The document object
	 * @param {String} sURI The document URI
	 */
	loadDocument : function(oDocument, sURI) {
		if (Fuji.Util.hasAttribute(oDocument.documentElement, 'parent')) {
			var sParentURI = oDocument.documentElement.getAttribute('parent');
			if (!document.getElementById(sParentURI)) {
				Fuji.reloadItemWithChild(sParentURI, oDocument, sURI);
				return;
			}
		}
		var oContent = Fuji.Util.getElementsByTagNameNS(oDocument,Fuji.NS,'content')[0];
		var oOldNode = document.getElementById(sURI);
		if (oOldNode) oOldNode.parentNode.removeChild(oOldNode);
		var oTargetDiv = document.createElement('div');
		oTargetDiv.id = sURI;
		for (var i=0; i < oContent.childNodes.length; i++) {
			oTargetDiv.appendChild(Fuji.Util.importNode(oContent.childNodes.item(i), true));
		}
		var oTargetId = oDocument.documentElement.getAttribute('loadcontext');
		var oTarget = document.getElementById(oTargetId);
		oTarget.appendChild(oTargetDiv);
		if (Fuji.Util.hasAttribute(oDocument.documentElement, 'parent')) {
			var sParentURI = oDocument.documentElement.getAttribute('parent');
			oTargetDiv.setAttribute('parent', sParentURI);
		}
		Fuji.selectDeckItem(oTargetDiv);
		var oLoadHandler = Fuji.Util.getElementsByTagNameNS(oDocument, Fuji.NS,'loadhandler');
		if (oLoadHandler.length) {
			oLoadHandler = oLoadHandler[0];
			if (oLoadHandler.hasChildNodes()) {
				eval(oLoadHandler.firstChild.nodeValue);
			}
			if (Fuji.Util.hasAttribute(oLoadHandler, 'function')) {
				var onload = oLoadHandler.getAttribute('function');
				eval(onload).apply(oTargetDiv, []);
			}
		}
	},
	
	/**
	 * Makes the given item oElm visible and hides all the other loaded items
	 * @param {Object} oElm The item to make visible
	 */
	selectDeckItem : function(oElm) {
		for (var i=0; i < oElm.parentNode.childNodes.length; i++) {
			var oDeckItem = oElm.parentNode.childNodes.item(i);
			if (oDeckItem.nodeType == 1) {
				oElm.parentNode.childNodes.item(i).style.display = 'none';
			}
		}
		oElm.style.display = '';
		if (Fuji.Util.hasAttribute(oElm, 'parent')) {
			Fuji.loadItem(oElm.getAttribute('parent'));
		}
	},
	
	/**
	 * Handles the DELETE request to delete the item (due to problems with YUI)
	 * @param {String} sURI The uri of the item to be deleted
	 * @param {String} sParentURI The uri of the item's parent
	 */
	httpDeleteAndReload : function(sURI, sParentURI) {
		var callback =
		{
			success: function(o) {Fuji.reloadItem(sParentURI); location.hash='#'+sParentURI},
			failure: Fuji.newErrorDialog,
			argument: []
		}
		YAHOO.util.Connect.asyncRequest('DELETE', sURI, callback, null); 
	},
	
	/**
	 * Handles the response of the data request for the edit item dialog
	 * @param {Object} oElm The response of the request
	 */		
	updateEditDialog : function(oElm) {
		var oSource = oElm.responseXML;
		var oEditTitle = document.getElementById('editT');
		var oEditContent = document.getElementById('editContent');
		oEditTitle.value = oSource.getElementById('title').innerHTML;
		oEditContent.value = oSource.getElementById('content').innerHTML;	

	},
	
	/**
	 * Handles the EDIT request to put the item (due to problems with YUI)
	 */
	editThread : function() {
		var sURI = document.getElementById('editEntryid').value;
		var callback =
		{
			success: function(o) {Fuji.reloadItem(sURI, 999)},
			failure: Fuji.newErrorDialog,
			argument: []
		}
		YAHOO.util.Connect.setForm('editF');
		YAHOO.util.Connect.asyncRequest('POST', sURI, callback, null); 
	},
	
	/**
	 * Starts the URL change listener
	 */	
	startUrlChangeListener : function() {
		setTimeout(Fuji.urlChangeListener, 300);
	},
	
	/**
	 * Listens for changes to the URL
	 */
	urlChangeListener : function() {
		if (location.hash != Fuji.currentURLHash) {
			Fuji.urlChangeCallback(location.hash);
			Fuji.currentURLHash = location.hash;
		}
		Fuji.startUrlChangeListener();
	},
	
	/**
	 * Loads an item when the URL has changed
	 * @param {String} hash The URI
	 */
	urlChangeCallback : function(hash) {
		var uri = hash.indexOf('#') != -1 ? hash.substr(hash.indexOf('#')+1) : hash;
		Fuji.loadItem(uri);
	},
	
	/**
	 * Initialises items when the page is loaded
	 */
	init : function() {
		Fuji.reloadItem('menu', 0);
		Fuji.loadItem('item/1');
		if (location.hash == '') location.hash='item/2';
		Fuji.startUrlChangeListener();
	},
	
	/**
	 * Handles the login request
	 */	
	login : function() {
		var callback = {
			success: function(o) {window.location.reload()},
			failure: Fuji.newErrorDialog
		}
		YAHOO.util.Connect.asyncRequest('POST', 'login', callback, null);
	},
	logout : function() {
		var callback = {
			success: function(o) {window.location.reload()},
			failure: Fuji.newErrorDialog
		}
		YAHOO.util.Connect.asyncRequest('POST', 'logout', callback, null);
 	},
	editGroup : function(sURI, rRead, rPost, rEdit, rDelete) {
		document.getElementById('editGroupF').setAttribute('action', sURI);
		document.getElementById('editGroupRead').checked = rRead;
		document.getElementById('editGroupPost').checked = rPost;
		document.getElementById('editGroupEdit').checked = rEdit;
		document.getElementById('editGroupDelete').checked = rDelete;
		Fuji.newDialog('editGroupDialog');
	},
	
	addTags : function(oElm, sTags) {
		oElm.value += sTags;
		oElm.focus();
	}
}
Fuji.Util = {
	// Opera and IE don't have these defined on the nodes themselves :(
	ELEMENT_NODE                : 1,
	ATTRIBUTE_NODE              : 2,
	TEXT_NODE                   : 3,
	CDATA_SECTION_NODE          : 4,
	ENTITY_REFERENCE_NODE       : 5,
	ENTITY_NODE                 : 6,
	PROCESSING_INSTRUCTION_NODE : 7,
	COMMENT_NODE                : 8,
	DOCUMENT_NODE               : 9,
	DOCUMENT_TYPE_NODE          : 10,
	DOCUMENT_FRAGMENT_NODE      : 11,
	NOTATION_NODE               : 12,
	
	/**
	 * Substitutes a missing getSelectedItems-function
	 */
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
	},
	
	/**
	 * Substitutes a missing getElementsByTagNameNS-function
	 */	
	getElementsByTagNameNS : function (oNode, sNs, sLocalName) {
		if (oNode.getElementsByTagNameNS) {
			return oNode.getElementsByTagNameNS(sNs, sLocalName);
		} else {
			var oElements = oNode.getElementsByTagName('fuji:' + sLocalName);
			var aResult = [];
			for (var i=0; i<oElements.length; i++) {
				var oElm = oElements.item(i);
				if (oElm.namespaceURI == sNs && oElm.nodeName == (oElm.prefix + ':' + sLocalName)) {
					aResult.push(oElm);
				}
			}
		}
		return aResult;
	},
	
	/**
	 * Substitutes a missing importNode-function
	 */	
	importNode : function (oNode, deep) {
		if(document.importNode) {
			return document.importNode(oNode, deep);
		} else {
			var oNew;
			switch (oNode.nodeType) {
				case Fuji.Util.ELEMENT_NODE:
					if (oNode.prefix) {
						sNodeName = oNode.nodeName.substring(oNode.nodeName.indexOf(':')+1);
					} else {
						sNodeName = oNode.nodeName;
					}
					oNew = document.createElement(sNodeName);
					for(var i = 0; i < oNode.attributes.length; i++){
						oNew.setAttribute(oNode.attributes[i].name, oNode.attributes[i].value);
					}
					if (oNode.style) oNew.style.cssText = oNode.style.cssText;
					if (oNode.getAttribute('style') != null) oNew.style.cssText = oNode.getAttribute('style');
					if (oNode.getAttribute('class') != null) oNew.className = oNode.getAttribute('class');
					
					if(deep && oNode.hasChildNodes()){
						for(var oChild = oNode.firstChild; oChild; oChild = oChild.nextSibling){
							var oImportedChildNode = Fuji.Util.importNode(oChild, true);
							if (oImportedChildNode) oNew.appendChild(oImportedChildNode);
						}
					}
					break;
				case Fuji.Util.TEXT_NODE:
					oNew = document.createTextNode(oNode.nodeValue);
					break;
			}
			return oNew;
		}
	},

	/**
	 * Substitutes a missing hasAttribute-function
	 */		
	hasAttribute : function (oNode, sName) {
		if (oNode.hasAttribute) {
			return oNode.hasAttribute(sName);
		} else {
			for (var i=0; i<oNode.attributes.length; i++) {
				if (oNode.attributes.item(i).nodeName == sName) return true;
			}
			return false;
		}
	}	
}

Fuji.Tree = {
	/**
	 * Finds and initialises all trees in the context
	 * @param {Object} oContextNode DOM context node to search in (optional, defaults to ???this???)
	 */
	init : function(oContextNode) {
		if (oContextNode === undefined) oContextNode = this;
		var oTrees = YAHOO.util.Dom.getElementsByClassName('tree', 'ul', oContextNode);
		for (var i=0; i<oTrees.length; i++) {
			Fuji.Tree.initTree(oTrees[i]);
		}
	},
	/**
	 * Initialises a tree list
	 * @param {Object} oTree DOM root node of the tree (<ul class="tree">)
	 */
	initTree : function(oTree) {
		var oTreeLIs = oTree.getElementsByTagName('li');
		for (var i=0; i<oTreeLIs.length; i++) Fuji.Tree.initTreeLI(oTreeLIs.item(i));
	},
	/**
	 * Add listeners to tree list items
	 * @param {Object} oNode Node in the tree
	 * @private
	 */
	initTreeLI : function(oNode) {
		// check whether first child (previous siblings of type element node)
		var oNodeCheck = oNode;
		while ((oNodeCheck = oNodeCheck.previousSibling) != null && oNodeCheck.nodeType != Fuji.Util.ELEMENT_NODE);
		if (oNodeCheck == null) YAHOO.util.Dom.addClass(oNode, 'first-child');
		else YAHOO.util.Dom.removeClass(oNode, 'first-child');
		// check whether last child (following siblings of type element node)
		var oNodeCheck = oNode;
		while ((oNodeCheck = oNodeCheck.nextSibling) != null && oNodeCheck.nodeType != Fuji.Util.ELEMENT_NODE);
		if (oNodeCheck == null) YAHOO.util.Dom.addClass(oNode, 'last-child');
		else YAHOO.util.Dom.removeClass(oNode, 'last-child');
		// check whether a leaf node
		if (!oNode.getElementsByTagName('ul').length) YAHOO.util.Dom.addClass(oNode, 'leaf');
		else YAHOO.util.Dom.removeClass(oNode, 'leaf');
		YAHOO.util.Event.addListener(oNode, 'click', Fuji.Tree.toggleOpenClose);
	},
	/**
	 * Handler to toggle the open/close class (when applicable)
	 * @param {Object} e Event object
	 * @private
	 */
	toggleOpenClose : function(e) {
		if (YAHOO.util.Event.getTarget(e) == this && this.getElementsByTagName('ul').length) {
			if (YAHOO.util.Dom.hasClass(this, 'open')) {
				YAHOO.util.Dom.replaceClass(this, 'open','closed');
			} else {
				YAHOO.util.Dom.replaceClass(this, 'closed', 'open');
			}
		}
		YAHOO.util.Event.stopPropagation(e);
	}
}
Fuji.Dialogs = {
	newDialog : function(sName) {
		if(!Fuji.Dialogs.Instances[sName])
		{
			var effect;
			if(Fuji.settings.visual_effects) effect = {effect:YAHOO.widget.ContainerEffect.FADE, duration:0.25};
			else effect = null;
			Fuji.Dialogs.Instances[sName] = new YAHOO.widget.Dialog(sName, { visible: false, fixedCenter: true, effect: effect,  width: '325px' });
			Fuji.Dialogs.Instances[sName].cfg.queueProperty("buttons", [ { text:Fuji.messages.ok, handler:Fuji.Dialogs.Settings[sName].handleSubmit, isDefault:true },
												   { text:Fuji.messages.cancel, handler:Fuji.Dialogs.Settings[sName].handleCancel} ]);
			Fuji.Dialogs.Instances[sName].cfg.queueProperty("onsuccess", Fuji.Dialogs.Settings[sName].onSuccess);
			Fuji.Dialogs.Instances[sName].render();
		}
		document.getElementById(sName).style.display = 'block';
		Fuji.Dialogs.Instances[sName].show();		
	},
	
	/**
	 * Creates a new delete group dialog (if it doesn't exist), and shows the dialog
	 * @param {String} sURI The uri of the group to be deleted
	 */	
	newDeleteGroupDialog : function(sURI) {
		var effect;
		if(Fuji.settings.visual_effects) effect = {effect:YAHOO.widget.ContainerEffect.FADE, duration:0.25};
		else effect = null;
		Fuji.deleteGroupDialog = new YAHOO.widget.SimpleDialog("dlg", { 
			width: "20em", 
			effect:effect, 
			fixedcenter:true,
			modal:true,
			draggable:false });

		Fuji.deleteGroupDialog.setHeader(Fuji.messages.warning);
		Fuji.deleteGroupDialog.setBody(Fuji.messages.sure_delete_group);
		Fuji.deleteGroupDialog.cfg.setProperty("icon", YAHOO.widget.SimpleDialog.ICON_WARN);
		var handleOK = function() {
			Fuji.httpDeleteAndReload(sURI, 'groups');
			this.hide();
		}
			var handleCancel = function() {
			this.hide();
		}
		Fuji.deleteGroupDialog.cfg.queueProperty("buttons", [ { text:Fuji.messages.ok, handler:handleOK }, { text:Fuji.messages.cancel, handler:handleCancel } ]);
		Fuji.deleteGroupDialog.render(document.body);
	},
	
	/**
	 * Creates an error message dialog
	 * @param oResponse The XMLHTTPRequest response-object
	 */
	newErrorDialog : function(oResponse) {
		if(!Fuji.errorDialog)
		{
			var effect;
			if(Fuji.settings.visual_effects) effect = {effect:YAHOO.widget.ContainerEffect.FADE, duration:0.25};
			else effect = null;			
			Fuji.errorDialog = new YAHOO.widget.SimpleDialog("dlg", { 
				width: "400px", 
				effect:effect, 
				fixedcenter:true,
				modal:true,
				draggable:false });
	
			Fuji.errorDialog.setHeader(Fuji.messages.error);
			
			var sErrorMessage = '<p>' + Fuji.messages.error_loading_resource + ' ' + oResponse.argument.uri + '.</p>';
			if(oResponse.status == '401')
			{
				sErrorMessage += '<p>' + Fuji.messages.error_insufficient_rights + '</p>';
				sErrorMessage += '<p>' + Fuji.messages.possible_reasons;
				sErrorMessage += '<ul><li>' + Fuji.messages.error_restricted_forum + '</li>';
				sErrorMessage += '<li>' + Fuji.messages.error_rights_revoked + '</li>';
				sErrorMessage += '<li>' + Fuji.messages.error_anonymous + '</li>';
				sErrorMessage += '<li>' + Fuji.messages.error_server_misconfiguration + '</li></ul></p>';
			}
			else if(oResponse.status == '404')
			{
				sErrorMessage += '<p>' + Fuji.messages.error_resource_cannot_be_found + '</p>';
				sErrorMessage += '<p>' + Fuji.messages.possible_reasons;
				sErrorMessage += '<ul><li>' + Fuji.messages.error_deleted_resource + '</li>';
				sErrorMessage += '<li>' + Fuji.messages.error_invalid_url + '</li>';
				sErrorMessage += '<li>' + Fuji.messages.error_server_misconfiguration + '</li></ul></p>';
			}
			else
			{
				sErrorMessage += '<p>' + Fuji.messages.error_unknown + ' ' + oResponse.status + ' ' + oResponse.statusText + '.</p>';
				sErrorMessage += '<p>' + Fuji.messages.possible_reasons;
				sErrorMessage += '<ul><li>' + Fuji.messages.error_server_misconfiguration + '</li>';
				sErrorMessage += '<li>' + Fuji.messages.error_bug + '</li></ul></p>';
				sErrorMessage += '<p>' + Fuji.messages.contact_administrator + '</p>'
			}
			Fuji.errorDialog.setBody(sErrorMessage);
			Fuji.errorDialog.cfg.setProperty("icon", YAHOO.widget.SimpleDialog.ICON_BLOCK);		
			
			var handleOK = function() {
				this.hide();
				Fuji.errorDialog = null;
			}
			
			Fuji.errorDialog.cfg.queueProperty("buttons", [ { text:Fuji.messages.ok, handler:handleOK } ]);
			Fuji.errorDialog.render(document.body);
		}
	},
		
	/**
	 * Creates a new loading dialog (if it doesn't exist), and shows the dialog
	 */	
	newLoadingDialog : function() {
		var effect;
		if(Fuji.settings.visual_effects) effect = {effect:YAHOO.widget.ContainerEffect.FADE, duration:0.25};
		else effect = null;		
		if(!Fuji.loadingDialog)
		{
			Fuji.loadingDialog = new YAHOO.widget.SimpleDialog("dlg", { 
				visible:false,
				width:"20em",
				effect:effect,
				fixedcenter:true,
				modal:true,
				close:false,
				draggable:false });
	
			Fuji.loadingDialog.setHeader(Fuji.messages.loading);
			Fuji.loadingDialog.setBody(Fuji.messages.please_wait);
			Fuji.loadingDialog.cfg.setProperty("icon", YAHOO.widget.SimpleDialog.ICON_INFO);		
			
			Fuji.loadingDialog.render(document.body);
		}
		Fuji.loadingDialog.show();
	},
	
	/**
	 * Creates a new edit item dialog (if it doesn't exist), and shows the dialog
	 * @param {String} sURI The uri of the item to be edit
	 */		
	newEditDialog : function(sURI) {
		var callback =
		{
			success: Fuji.updateEditDialog,
			failure: Fuji.newErrorDialog,
			argument: []
		}
		document.getElementById('editF').action = sURI;
		YAHOO.util.Connect.asyncRequest('GET', sURI + '/source', callback, null); 		
		document.getElementById('editDialog').style.display = 'block';		
		Fuji.Dialogs.newDialog('editDialog');
	},
	
	/**
	 * Creates a new create item dialog (if it doesn't exist), and shows the dialog
	 * @param {String} sURI The uri of the item to add a new item to
	 * @param {Int} iType The type of the new item
	 */
	newCreateDialog : function(sURI, iType) {
		document.getElementById('createType').value = iType; 
		document.getElementById('createF').action = sURI;
		Fuji.Dialogs.newDialog('createDialog');
	},
	
	/**
	 * Creates a new delete item dialog (if it doesn't exist), and shows the dialog
	 * @param {String} sURI The uri of the item to be deleted
	 * @param {String} sParentURI The uri of the item's parent
	 */
	newDeleteDialog : function(sURI, sParentURI) {
		var effect;
		if(Fuji.settings.visual_effects) effect = {effect:YAHOO.widget.ContainerEffect.FADE, duration:0.25};
		else effect = null;
		Fuji.deleteDialog = new YAHOO.widget.SimpleDialog("dlg", { 
			width: "20em", 
			effect:effect, 
			fixedcenter:true,
			modal:true,
			draggable:false });

		Fuji.deleteDialog.setHeader(Fuji.messages.warning);
		Fuji.deleteDialog.setBody(Fuji.messages.sure_delete_item);
		Fuji.deleteDialog.cfg.setProperty("icon", YAHOO.widget.SimpleDialog.ICON_WARN);		
		
		var handleOK = function() {
			Fuji.httpDeleteAndReload(sURI, sParentURI);
			this.hide();
		}
		var handleCancel = function() {
			this.hide();
		}
		
		Fuji.deleteDialog.cfg.queueProperty("buttons", [ { text:Fuji.messages.ok, handler:handleOK }, { text:Fuji.messages.cancel, handler:handleCancel } ]);
		Fuji.deleteDialog.render(document.body);
	}
}
Fuji.Dialogs.Instances = { /* All dialog instance objects created using newDialog are stored in here */ }
Fuji.Dialogs.Settings = { }
Fuji.Dialogs.Settings.serverSettingsDialog = {
	onSuccess : function() { location.reload(); },
	handleSubmit : function() { this.submit(); this.hide(); },
	handleCancel : function() { this.cancel(); this.hide(); }
}
Fuji.Dialogs.Settings.prefDialog = {
	onSuccess : function() { location.reload(); },
	handleSubmit : function() {
		var oPrefPass1 = document.getElementById('prefPass1');
		var oPrefPass2 = document.getElementById('prefPass2');
		var oPrefEmail = document.getElementById('prefEmail');
		var oPrefPhoto = document.getElementById('prefPhoto');			
		if(oPrefPass1.value != oPrefPass2.value)
			alert(Fuji.messages.passwords_do_not_match);
		else
		{
			if(oPrefEmail.value == '')
				alert(Fuji.messages.email_empty);
			else
			{
				if(oPrefPhoto.value && oPrefPhoto.value.indexOf('http://') != 0)
					alert(Fuji.messages.invalid_url);
				else
				{
					this.submit();
					this.hide();
				}
			}
		}
	},
	handleCancel : function() { this.cancel(); this.hide(); }
}
Fuji.Dialogs.Settings.newGroupDialog = {
	onSuccess : function() { Fuji.reloadItem("groups/"); },
	handleSubmit : function() { this.submit(); this.reset(); this.hide(); },
	handleCancel : function() { this.cancel(); this.reset(); this.hide(); },
}
Fuji.Dialogs.Settings.editGroupDialog = {
	onSuccess : function() { Fuji.reloadItem("groups/"); },
	handleSubmit : function() { this.submit(); this.reset(); this.hide(); },
	handleCancel : function() { this.cancel(); this.reset(); this.hide(); },
}
Fuji.Dialogs.Settings.configRightsDialog = {
	onSuccess : function() { },
	handleSubmit : function() { this.submit(); this.reset(); this.hide(); },
	handleCancel : function() { this.cancel(); this.reset(); this.hide(); },
}
Fuji.Dialogs.Settings.createDialog = {
	onSuccess : function() { /* reload the thread or forum */ },
	handleSubmit : function() { this.submit(); this.reset(); this.hide(); },
	handleCancel : function() { this.cancel(); this.reset(); this.hide(); },
}
Fuji.Dialogs.Settings.editDialog = {
	onSuccess : function() { /* reload the thread or forum */ },
	handleSubmit : function() { this.submit(); this.reset(); this.hide(); },
	handleCancel : function() { this.cancel(); this.reset(); this.hide(); },
}