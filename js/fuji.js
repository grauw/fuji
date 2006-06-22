Fuji = {
	NS : 'http://fuji.sourceforge.net/ns',
	createDialog : null,			//pointer to the create dialog
	deleteDialog : null,			//pointer to the delete dialog
	editDialog : null,				//pointer to the edit dialog
	preferencesDialog : null,		//pointer to the preferences dialog
	serverSettingsDialog : null,	//pointer to the server settings dialog
	newGroupDialog : null,			//pointer to the new group dialog
	editGroupDialog : null,			//pointer to the edit group dialog
	deleteGroupDialog : null,		//pointer to the delete group dialog
	errorDialog : null,				//pointer to the error dialog	
	configRightsDialog : null,		//pointer to the config rights dialog
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
		document.getElementById('rsslink').href = 'rss/' + uri.substring(0, uri.length-1).replace('item/','');
		document.getElementById('atomlink').href = 'atom/' + uri.substring(0, uri.length-1).replace('item/','');		
	},
	
	/**
	 * Reloads the item with the specified URI, and go up the tree depth nodes
	 * @param {String} uri The URI to the item
	 * @param {Int} depth Number of parents to reload recursively (optional, default: 0 = none)
	 */	
	reloadItem : function(uri, depth) {
		Fuji.newLoadingDialog();
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
		Fuji.newLoadingDialog();
		var callback =
		{
			success: Fuji.loadSuccess,
			failure: Fuji.loadFailure,
			argument: { uri: uri, oDocument: oDocument, sURI: sURI }
		}
		YAHOO.util.Connect.asyncRequest('GET', uri, callback, null);
	},
	
	loadFailure : function(oXMLHttpObject) {
		Fuji.loadingDialog.hide();
		Fuji.newErrorDialog(oXMLHttpObject);
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
	 * Loads the menu
	 */
	loadMenu : function() {
		var callback =
		{
			success: Fuji.loadSuccess,
			failure: Fuji.newErrorDialog,
			argument: []
		}
		YAHOO.util.Connect.asyncRequest('GET', 'menu/', callback, null); 
	},
	
	/**
	 * Creates a new create item dialog (if it doesn't exist), and shows the dialog
	 * @param {Int} iId The id of the item to add a new item to
	 * @param {Int} iType The type of the new item
	 */
	newCreateDialog : function(iId, iType) {
		document.getElementById('createType').value = iType; 
		document.getElementById('createRef').value = iId;
		if(!Fuji.createDialog)
		{
			var effect;
			if(Fuji.settings.visual_effects) effect = {effect:YAHOO.widget.ContainerEffect.FADE, duration:0.25};
			else effect = null;			
			Fuji.createDialog = new YAHOO.widget.Dialog("createDialog", { visible: false, fixedCenter: true, effect:effect, width: '300px' });
			var handleCancel = function() {
				this.cancel();
				document.getElementById('createF').reset();			
				this.hide();
			}
			var handleSubmit = function() {
				this.submit();
				document.getElementById('createF').reset();			
				this.hide();
				Fuji.reloadItem("item/" + document.getElementById('createRef').value + '/');
				}
			Fuji.createDialog.cfg.queueProperty("buttons", [ { text:Fuji.messages.ok, handler:handleSubmit, isDefault:true },
												   { text:Fuji.messages.cancel, handler:handleCancel } ]);
			Fuji.createDialog.cfg.queueProperty("onsuccess", Fuji.reloadThreads);
			Fuji.createDialog.render();
		}
		document.getElementById('createDialog').style.display = 'block';
		Fuji.createDialog.show();
	},
	
	/**
	 * Creates a new delete item dialog (if it doesn't exist), and shows the dialog
	 * @param {Int} iId The id of the item to be deleted
	 * @param {Int} iPId The id of the item's parent
	 */	
	newDeleteDialog : function(iId, iPId) {
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
			Fuji.deleteItem(iId, iPId);
			this.hide();
		}
		var handleCancel = function() {
			this.hide();
		}
		
		Fuji.deleteDialog.cfg.queueProperty("buttons", [ { text:Fuji.messages.ok, handler:handleOK }, { text:Fuji.messages.cancel, handler:handleCancel } ]);
		Fuji.deleteDialog.render(document.body);
	},
	
	/**
	 * Handles the DELETE request to delete the item (due to problems with YUI)
	 * @param {Int} iId The id of the item to be deleted
	 * @param {Int} iPId The id of the item's parent
	 */			
	deleteItem : function(iId, iPId) {
		var callback =
		{
			success: function(o) {Fuji.reloadItem('item/' + iPId + '/'); location.hash='#item/' + iPId + '/'},
			failure: Fuji.newErrorDialog,
			argument: []
		}
		YAHOO.util.Connect.asyncRequest('DELETE', 'item/' + iId + '/', callback, null); 
	},
	
	/**
	 * Creates a new edit item dialog (if it doesn't exist), and shows the dialog
	 * @param {Int} iId The id of the item to be edit
	 */		
	newEditDialog : function(iId) {
		if(!Fuji.editDialog)		
		{
			var effect;
			if(Fuji.settings.visual_effects) effect = {effect:YAHOO.widget.ContainerEffect.FADE, duration:0.25};
			else effect = null;
			Fuji.editDialog = new YAHOO.widget.Dialog("editDialog", { visible: false, fixedCenter: true, effect:effect, width: '300px' });
			var handleCancel = function() {
				document.getElementById('editF').reset();
				this.cancel();
			}
			var handleSubmit = function() {
				Fuji.editThread();
				this.hide();
			}
			Fuji.editDialog.cfg.queueProperty("buttons", [ { text:Fuji.messages.ok, handler:handleSubmit, isDefault:true },
												   { text:Fuji.messages.cancel, handler:handleCancel} ]);
			Fuji.editDialog.render();
		}
		var callback =
		{
			success: Fuji.updateEditDialog,
			failure: Fuji.newErrorDialog,
			argument: []
		}
		document.getElementById('editEntryid').value = iId;
		YAHOO.util.Connect.asyncRequest('GET', 'item/' + iId + '/source', callback, null); 		
		document.getElementById('editDialog').style.display = 'block';		
		Fuji.editDialog.show();
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
	 * Handles the EDIT request to delete the item (due to problems with YUI)
	 */		
	editThread : function() {
		var iId = document.getElementById('editEntryid').value;
		var callback =
		{
			success: function(o) {Fuji.reloadItem('item/' + iId + '/', 999)},
			failure: Fuji.newErrorDialog,
			argument: []
		}
		YAHOO.util.Connect.setForm('editF');
		YAHOO.util.Connect.asyncRequest('POST', 'item/' + iId + '/', callback, null); 
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
	 * Creates a new server preferences dialog (if it doesn't exist), and shows the dialog
	 */
	newServerSettingsDialog : function() {
		if(!Fuji.serverSettingsDialog)
		{
			var effect;
			if(Fuji.settings.visual_effects) effect = {effect:YAHOO.widget.ContainerEffect.FADE, duration:0.25};
			else effect = null;
			Fuji.serverSettingsDialog = new YAHOO.widget.Dialog("serverSettingsDialog", { visible: false, fixedCenter: true, effect: effect,  width: '325px' });
			var handleCancel = function() {
				this.cancel();
			}
			var handleSubmit = function() {
				this.submit();
				this.hide();
			}
			Fuji.serverSettingsDialog.cfg.queueProperty("buttons", [ { text:Fuji.messages.ok, handler:handleSubmit, isDefault:true },
												   { text:Fuji.messages.cancel, handler:handleCancel} ]);
			Fuji.serverSettingsDialog.cfg.queueProperty("onsuccess", function() {location.reload();});
			Fuji.serverSettingsDialog.render();
		}
		document.getElementById('serverSettingsDialog').style.display = 'block';
		Fuji.serverSettingsDialog.show();
	},

	/**
	 * Creates a new user preferences dialog (if it doesn't exist), and shows the dialog
	 */
	newPreferencesDialog : function() {
		if(!Fuji.preferencesDialog)
		{
			var effect;
			if(Fuji.settings.visual_effects) effect = {effect:YAHOO.widget.ContainerEffect.FADE, duration:0.25};
			else effect = null;
			Fuji.preferencesDialog = new YAHOO.widget.Dialog("prefDialog", { visible: false, fixedCenter: true, effect: effect,  width: '325px' });
			var handleCancel = function() {
				this.cancel();
			}
			var handleSubmit = function() {
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
			}
			Fuji.preferencesDialog.cfg.queueProperty("buttons", [ { text:Fuji.messages.ok, handler:handleSubmit, isDefault:true },
												   { text:Fuji.messages.cancel, handler:handleCancel} ]);
			Fuji.preferencesDialog.cfg.queueProperty("onsuccess", function() {location.reload();});
			Fuji.preferencesDialog.render();
		}
		document.getElementById('prefDialog').style.display = 'block';
		Fuji.preferencesDialog.show();
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
	 * Activates the ConfigRightsDialog
	 */
	setConfigRightsDialog : function() {
		var effect;
		if(Fuji.settings.visual_effects) effect = {effect:YAHOO.widget.ContainerEffect.FADE, duration:0.25};
		else effect = null;		
		Fuji.configRightsDialog = null;
		if(!Fuji.configRightsDialog)
		{
			Fuji.configRightsDialog = new YAHOO.widget.Dialog("configRightsDialog", { visible: false, fixedCenter: true, effect:effect,  width: '325px' });
			var handleCancel = function() {
				this.cancel();
				document.getElementById('configRightsF').reset();
				this.hide();
			}
			var handleSubmit = function() {
				alert('nu!');
				this.submit();
				document.getElementById('configRightsF').reset();
				this.hide();
			}
			Fuji.configRightsDialog.cfg.queueProperty("buttons", [ { text:Fuji.messages.ok, handler:handleSubmit, isDefault:true },
												   { text:Fuji.messages.cancel, handler:handleCancel} ]);
			Fuji.configRightsDialog.cfg.queueProperty("onsuccess", function() {});
			Fuji.configRightsDialog.render();
		}
		document.getElementById('configRightsDialog').style.display = 'block';
		Fuji.configRightsDialog.show();

	},
	/**
	 * Initialises items when the page is loaded
	 */
	init : function() {
		Fuji.loadMenu();
		Fuji.loadItem('item/1/');
		if (location.hash == '') location.hash='#item/2/';
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
		YAHOO.util.Connect.asyncRequest('POST', 'login', callback);
	},
	logout : function() {
		var callback = {
			success: function(o) {window.location.reload()},
			failure: Fuji.newErrorDialog
		}
		YAHOO.util.Connect.asyncRequest('POST', 'logout', callback);
 	},
	newGroup : function() {
		if(!Fuji.newGroupDialog)
		{
			var effect;
			if(Fuji.settings.visual_effects) effect = {effect:YAHOO.widget.ContainerEffect.FADE, duration:0.25};
			else effect = null;
			Fuji.newGroupDialog = new YAHOO.widget.Dialog("newGroupDialog", { visible: false, fixedCenter: true, effect:effect, width: '500px' });
			var handleCancel = function() {
				this.cancel();
				alert('error');
				document.getElementById('newGroupF').reset();			
				this.hide();
			}
			var handleSubmit = function() {
				this.submit();
				document.getElementById('newGroupF').reset();			
				this.hide();
				}
			Fuji.newGroupDialog.cfg.queueProperty("buttons", [ { text:Fuji.messages.ok, handler:handleSubmit, isDefault:true },
												   { text:Fuji.messages.cancel, handler:handleCancel } ]);
			Fuji.newGroupDialog.cfg.queueProperty("onsuccess", function() {Fuji.reloadItem("groups/");});
			Fuji.newGroupDialog.render();
		}
		document.getElementById('newGroupDialog').style.display = 'block';
		Fuji.newGroupDialog.show();
	},
	editGroup : function(groupId, rRead, rPost, rEdit, rDelete) {
		document.getElementById('editGroupId').value = groupId;
		document.getElementById('editGroupRead').checked = rRead;
		document.getElementById('editGroupPost').checked = rPost;
		document.getElementById('editGroupEdit').checked = rEdit;
		document.getElementById('editGroupDelete').checked = rDelete;
		if(!Fuji.editGroupDialog)
		{
			var effect;
			if(Fuji.settings.visual_effects) effect = {effect:YAHOO.widget.ContainerEffect.FADE, duration:0.25};
			else effect = null;
			Fuji.editGroupDialog = new YAHOO.widget.Dialog("editGroupDialog", { visible: false, fixedCenter: true, effect:effect, width: '500px' });
			var handleCancel = function() {
				this.cancel();
				document.getElementById('editGroupF').reset();
				this.hide();
			}
			var handleSubmit = function() {
				this.submit();
				document.getElementById('editGroupF').reset();
				this.hide();
			}
			Fuji.editGroupDialog.cfg.queueProperty("buttons", [ { text:Fuji.messages.ok, handler:handleSubmit, isDefault:true },
												   { text:Fuji.messages.cancel, handler:handleCancel } ]);
			Fuji.editGroupDialog.cfg.queueProperty("onsuccess", function() {Fuji.reloadItem("groups/");});
			Fuji.editGroupDialog.render();
		}
		document.getElementById('editGroupDialog').style.display = 'block';
		Fuji.editGroupDialog.show();
	},
	/**
	 * Creates a new delete group dialog (if it doesn't exist), and shows the dialog
	 * @param {Int} iId The id of the group to be deleted
	 */	
	newDeleteGroupDialog : function(iId) {
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
			Fuji.deleteGroup(iId);
			Fuji.reloadItem("groups/");
			this.hide();
		}
			var handleCancel = function() {
			this.hide();
		}
		Fuji.deleteGroupDialog.cfg.queueProperty("buttons", [ { text:Fuji.messages.ok, handler:handleOK }, { text:Fuji.messages.cancel, handler:handleCancel } ]);
		Fuji.deleteGroupDialog.render(document.body);
	},
	
	/**
	 * Handles the DELETE request to delete the group
	 * @param {Int} iId The id of the group to be deleted
	 *TODO param toId: move user in this group to toId
	 */			
	deleteGroup : function(iId) {
		var callback =
		{
			success: function(o) { Fuji.reloadItem('groups/' + iId ); location.hash='#groups/' + iId;},
			failure: Fuji.loadFailure,
			argument: { uri: 'groups/' + iId }
		}
		YAHOO.util.Connect.asyncRequest('DELETE', 'groups/' + iId, callback, null);
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

